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
}

?>