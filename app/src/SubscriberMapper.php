<?php

/**
 * Class SubscriberMapper interact with the BDD to return Subscriber
 */
class SubscriberMapper {

    /**
     * PDO Object to interact with the BDD
     *
     * @var PDO
     */
    protected $_pdo;

    /**
     * Constructor of class SubscriberMapper
     *
     * @param PDO $pdo
     */
    function __construct( PDO $pdo ) {
        $this->_pdo = $pdo;
    }  

    /**
     * Return an array with all Subscriber in the Database.
     *
     * @return array 
     */
    function getAllSubscribers() {
        
        return array();
    }
}

?>