<?php 

namespace Model;

/**
 * Class Subscriber create object Subscriber
 * @deprecated 
 */
class Subscriber {

    /**
     * Id of our Subscriber 
     *
     * @var int $_id
     */
    private $_id;
    
    /**
     * Name of our Subscriber
     *
     * @var string $_name
     */
    private $_name;

    /**
     * Email of our Subscriber
     *
     * @var string $_email 
     */
    private $_email;

    /**
     * State of our Subscriber
     *
     * @var string $_state
     */
    private $_state;

    /**
     * Constructor of our class Subscriber
     *
     * @param integer $id
     * @param string $name
     * @param string $email
     * @param string $state
     * @return void
     */
    function __construct( int $id, string $name, string $email, string $state ) {
        $this->_id = $id;
        $this->_name = $name;
        $this->_email = $email;
        $this->_state = $state;
    }
    
    /**
     * Return id of our Subscriber
     *
     * @return int
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Set id of our Subscriber
     *
     * @param integer $id
     * @return void
     */
    public function setId( int $id ) {
        $this->_id = $id;
    }

    /**
     * Return name of our Subscriber
     *
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Set name of our Subscriber
     *
     * @param string $name
     * @return void
     */
    public function setName( string $name ) {
        $this->_name = $name;
    }

    /**
     * Return email of our Subscriber
     *
     * @return void
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * Set email of our Subscriber
     *
     * @param string $email
     * @return void
     */
    public function setEmail( string $email ) {
        $this->_email = $email;
    }

    /**
     * Return state of our Subscriber
     *
     * @return void
     */
    public function getState() {
        return $this->_state;
    }

    /**
     * Set the state of our Subscriber
     *
     * @param string $state
     * @return void
     */
    public function setState( string $state ) {
        $this->_state = $state;
    }
}

?>