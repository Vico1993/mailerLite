<?php 

namespace Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Model\SubscriberMapper as SubscriberMapper;
use \Model\FieldMapper as FieldMapper;

class FieldController {

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
     * FieldMapper permet to interact with the BDD to get all our field
     *
     * @var FieldMapper
     */
    protected $_fieldMapper;

    public function __construct( \Slim\Container $container ) {
        $this->_container = $container;
        $this->_subscriberMapper = new SubscriberMapper( $container->db );
        $this->_fieldMapper = new FieldMapper( $container->db );
    }

    /**
     * Methode who will return a specific field if id given or all fields
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function get(Request $request, Response $response, array $args) {
        $data = array();
        
        // Check if we have an id in the URL
        if ( isset( $args['id'] ) && !empty( $args['id'] ) ) {
            $data = $this->_fieldMapper->getFieldById( $args['id'] );
        }
        else {
            $data = $this->_fieldMapper->getAllField();
        }
        
        return $response->withJson( $data );
    }

    /**
     * Method to return all field with a specific type [and specifc user]
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function filter(Request $request, Response $response, array $args) {
        $data = array();

        // Check if we have an id in the URL
        if ( isset( $args['id_type'] ) && !empty( $args['id_type'] ) ) {
            $id_subscriber = ( isset( $args['id_subscriber'] ) && !empty( $args['id_subscriber'] ) ) ? filter_var( $args['id_subscriber'], FILTER_VALIDATE_INT ) : 0;
            $data = $this->_fieldMapper->getFieldFiltered( filter_var( $args['id_type'], FILTER_VALIDATE_INT ) , $id_subscriber);
        }
        else {
            // If no filter send all subscribers
            $data = $this->_fieldMapper->getAllField();
        }

        return $response->withJson( $data );
    }

    /**
     * Return all field for a user.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function subscriber(Request $request, Response $response, array $args) {
        $data = array();

        // Check if we have an id in the URL
        if ( isset( $args['id_subscriber'] ) && !empty( $args['id_subscriber'] ) ) {
            $data = $this->_fieldMapper->getFieldBySubscriberId( filter_var( $args['id_subscriber'], FILTER_VALIDATE_INT ) );
        }
        else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "id not found or incorrect. Please send us a valid id.";
        }

        return $response->withJson( $data );
    }

    /**
     * Create a field
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add(Request $request, Response $response) { 
        $data = array();
        $postData = $request->getParsedBody();

        if ( !empty( $postData['title'] ) ) {
            if ( !empty( $postData['id_type'] ) && $this->_fieldMapper->checkTypeById( filter_var( $postData['id_type'], FILTER_VALIDATE_INT) ) ) {
                $subscriber = $this->_subscriberMapper->getSubscriberById( filter_var( $postData['id_subscriber'], FILTER_VALIDATE_INT ) );
                if( !empty( $subscriber ) ) {
                    $this->_fieldMapper->saveField( $subscriber['id'], filter_var( $postData['title'], FILTER_SANITIZE_STRING), filter_var( $postData['id_type'], FILTER_VALIDATE_INT));
                    $data['success'] = "field created";
                } else {
                    $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Can't find this subscriber.. Please check your id_subscriber.";
                }
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Type of your field not found. Please send us a valid id_type";
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "Title of your field not found or incorrect. Please send us a valid title.";
        }
        
        return $response->withJson( $data );
    }

    /**
     * Update a field
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function update(Request $request, Response $response, array $args) {
        $data = array();
        $postData = $request->getParsedBody();

        if ( !empty( $args['id'] ) && intval( $args['id'] ) ) { 
            $field = $this->_fieldMapper->getFieldById( filter_var( $args['id'], FILTER_VALIDATE_INT) );
            if ( !empty( $field ) ) {
                $field['title'] = ( !empty( $postData['title'] ) ) ? filter_var( $postData['title'], FILTER_SANITIZE_STRING ) : $field['title'];
                $field['id_type'] = ( !empty( $postData['id_type'] ) ) ? filter_var( $postData['id_type'], FILTER_SANITIZE_STRING ) : $field['id_type'];
                $field['id_subscriber'] = ( !empty( $postData['id_subscriber'] ) ) ? filter_var( $postData['id_subscriber'], FILTER_SANITIZE_STRING ) : $field['id_subscriber'];
                
                $this->_fieldMapper->saveField( $field['id_subscriber'], $field['title'], $field['id_type'], $args['id'] );
                $data['success'] = "field updated";
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Can't find this field.. Please check your id.";
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "id not found or incorrect. Please send us a valid id.";
        }

        return $response->withJson( $data );
    }

    /**
     * Delete a field
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function delete(Request $request, Response $response, $args) { 
        $data = array();
        
        if ( !empty( $args['id'] ) && intval( $args['id'] ) ) {
            $field = $this->_fieldMapper->getFieldById( $args['id'] );
            if( !empty( $field ) ) { 
                $this->_fieldMapper->deleteField( $field['id'] );
                $data['success'] = "field deleted";
            } else {
                $response = $response->withStatus( 400 );
                $data[ 'error' ] = "Can't find this field.. Please check your id."; 
            }
        } else {
            $response = $response->withStatus( 400 );
            $data[ 'error' ] = "id not found or incorrect. Please send us a valid id.";
        }

        return $response->withJson( $data );
    }
}

?>