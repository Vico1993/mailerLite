<?php 

namespace Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Model\SubscriberMapper as SubscriberMapper;

class SubscriberController {
    protected $container;

    // constructor receives container instance
    public function __construct( \Slim\Container $container ) {
        $this->container = $container;
    }

    public function get(Request $request, Response $response, array $args) {
        // Data to return 
        $data = array();
        $subscriberMapper = new SubscriberMapper($this->container->db); 

        // Get an id in url.
        if ( isset( $args['id'] ) && !empty( $args['id'] ) ) {
            $id = $args['id'];
            $data = $subscriberMapper->getSubscriberById( $id );
        }
        else {
            $data = $subscriberMapper->getAllSubscribers();
        }
        
        return $response->withJson($data);
    }
}
?>