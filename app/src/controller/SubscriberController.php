<?php 

namespace Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Model\SubscriberMapper as SubscriberMapper;

class SubscriberController {

    /**
     * Container who will get all data from our Slim App ( example the bdd )
     *
     * @var \Slim\Container
     */
    protected $_container;

    /**
     * SubscriberMapper permet to interact with the BDD to get all our subscriber
     *
     * @var SubscriberMapper
     */
    protected $_subscriberMapper;

    /**
     * Controller who will responds to a specifc routes
     *
     * @param \Slim\Container $container
     */
    public function __construct( \Slim\Container $container ) {
        $this->_container = $container;
        $this->_subscriberMapper = new SubscriberMapper( $container->db );
    }

    /**
     * Methode who will return a specific subscriber if id given or all subscribers
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function get(Request $request, Response $response, array $args) {
        // Data to return 
        $data = array();
        
        // Check if we have an id in the URL
        if ( isset( $args['id'] ) && !empty( $args['id'] ) ) {
            $data = $this->_subscriberMapper->getSubscriberById( $args['id'] );
        }
        else {
            $data = $this->_subscriberMapper->getAllSubscribers();
        }
        
        return $response->withJson( $data );
    }

    /**
     * Filter all subscriber with theire statues.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function filters(Request $request, Response $response, array $args) {
        $data = array();

        // Check if we have an id in the URL
        if ( isset( $args['id_state'] ) && !empty( $args['id_state'] ) ) {
            if ( $this->_subscriberMapper->checkStateById( $args['id_state'] ) ) {
                $data = $this->_subscriberMapper->getSubscriberByState( $args['id_state'] );
            }
        }
        else {
            // If no filter send all subscribers
            $data = $this->_subscriberMapper->getAllSubscribers();
        }

        return $response->withJson( $data );
    }

    /**
     * Create a subscriber
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add(Request $request, Response $response) {
        $data = array();
        $postData = $request->getParsedBody();

        // Clean data receive in POST
        // checkdnsrr will check if domain of email is reacheable
        if ( !empty( $postData['email'] ) && $this->checkEmail( $postData['email'] ) ) {
            if ( !empty( $postData['name'] ) ) {
                // at the begining the subscriber is unconfirmed
                $id_state = 5;
                $this->_subscriberMapper->saveSubscriber( filter_var($postData['name'], FILTER_SANITIZE_STRING), filter_var($postData['email'], FILTER_SANITIZE_STRING), $id_state );
                $data['success'] = "subscriber created";
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Name not found. Please send us a valid Name";
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "Email not found or incorrect. Please send us a valid Email.";
        }
        
        return $response->withJson( $data );
    }

    /**
     * Update a subscriber.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, $args) {
        $data = array();
        $postData = $request->getParsedBody();

        // Test if we receive an ID
        // If it's an integer
        // And if a subscriber got this ID
        if ( !empty( $args['id'] ) && intval( $args['id'] ) ) { 
            $subscriber = $this->_subscriberMapper->getSubscriberById( $args['id'] );
            if( !empty( $subscriber ) ) {
                $subscriber['name'] = ( !empty( $postData['name'] ) ) ? filter_var( $postData['name'], FILTER_SANITIZE_STRING ) : $subscriber['name'];
                $subscriber['email'] = ( !empty( $postData['email'] ) && $this->checkEmail( $postData['email'] ) ) ? $postData['email'] : $subscriber['email'];
                $subscriber['id_state'] = ( !empty( $postData['id_state'] ) && $this->_subscriberMapper->checkStateById( filter_var( $postData['id_state'], FILTER_VALIDATE_INT ) ) ) ? filter_var( $postData['id_state'], FILTER_VALIDATE_INT ) : $this->_subscriberMapper->getSubscriberStateByValue( $subscriber['state'] );

                $this->_subscriberMapper->saveSubscriber( $subscriber['name'], $subscriber['email'], $subscriber['id_state'], $subscriber['id'] );
                $data['success'] = "subscriber updated";
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Can't find this subscriber.. Please check your id.";
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "id not found or incorrect. Please send us a valid id.";
        }

        return $response->withJson( $data );
    }

    public function delete(Request $request, Response $response, $args) {
        $data = array();

        if ( !empty( $args['id'] ) && intval( $args['id'] ) ) {
            $subscriber = $this->_subscriberMapper->getSubscriberById( $args['id'] );
            if( !empty( $subscriber ) ) { 
                $this->_subscriberMapper->deleteSubscriber( $subscriber['id'] );
                $data['success'] = "subscriber deleted";
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Can't find this subscriber.. Please check your id."; 
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "id not found or incorrect. Please send us a valid id.";
        }

        return $response->withJson( $data );
    }

    /**
     * Method to check email, if ok return the email
     *
     * @param string $email
     * @return boolean|string
     */
    private function checkEmail( string $email ) {
        $ret = false;
        // Verif email format
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            // Verif domain of email
            list( $username, $mailDomain) = explode( "@", $email );
            if ( checkdnsrr( $mailDomain, "MX" ) ) {
                $ret = $email;
            }
        }

        return $ret;
    }
}
?>