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
     * @param array $args
     * @return Response
     */
    public function add(Request $request, Response $response) {
        $data = array();
        $postData = $request->getParsedBody();

        // filter_var($data['email'], FILTER_SANITIZE_STRING);
        // filter_var($data['name'], FILTER_SANITIZE_STRING);
        // filter_var($data['state'], FILTER_SANITIZE_STRING);
        

        // @TODO : ADD verification on state
        $data = $postData;

        return $response->withJson( $data );
    }
}
?>