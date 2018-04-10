<?php 

namespace Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Model\SubscriberMapper as SubscriberMapper;

class SubscriberController {

    /**
     * Container who will get all data from our Slim App ( example the bdd )
     *
     * @var [type]
     */
    protected $container;

    /**
     * Controller who will responds to a specifc routes
     *
     * @param \Slim\Container $container
     */
    public function __construct( \Slim\Container $container ) {
        $this->container = $container;
    }

    /**
     * Methode who will return a specific subscriber if id given or all subscribers
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
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