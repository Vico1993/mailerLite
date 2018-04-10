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
     * Insert a new Subscriber
     *
     * @param Subscriber $sub
     * @return integer
     */
    function addSubscriber( Subscriber $sub ) {
        $query = $this->_pdo->prepare( 'INSERT INTO subscriber (id_state , name, email) VALUES (:id_state, :name, :email)' );
		$query->bindValue( ':id_state', $user->getSubscriberStateByValue( $sub->getState() ) );
		$query->bindValue( ':name', $sub->getName( ) );
		$query->bindValue( ':email', $sub->getEmail( ) );
        $query->execute();
        
		return $this->_pdo->lastInsertId();
    }
    
    /**
     * Save/Update a Subscriber
     *
     * @param Subscriber $sub
     * @return void
     */
    function saveSubscriber( Subscriber $sub ) {
        $query = $this->_pdo->prepare( 'UPDATE MyGuests SET id=:id, name=:name, email=:email WHERE id = :id' );
		$query->bindValue( ':id', $sub->getId() );
		$query->bindValue( ':state', $sub->getState() );
		$query->bindValue( ':name', $sub->getName( ) );
		$query->bindValue( ':email', $sub->getEmail( ) );
        $query->execute();
    }

    /**
     * Return an array with all Subscriber in the Database.
     *
     * @return array 
     */
    function getAllSubscribers() {
        $subscribers = [];
        
        $query = $this->_pdo->prepare( 'SELECT * FROM subs ORDER BY subs.state' );
		$query->execute();

		while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {
            $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            
			$subscribers[] = $subscriber;
		}

		return $subscribers;
    }

    /**
     * Return a Subscriber with this id
     *
     * @param integer $id
     * @return NULL|Subscriber
     */
    function getSubscriberById( int $id ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM subs WHERE id = :id' );
		$query->bindValue( ':id', $id );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
            $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            
			return $subscriber;
		}

		return NULL;
    }

    /**
     * Return all Subscriber with the same state.
     *
     * @param integer $id_state
     * @return void
     */
    function getSubscriberByState( int $id_state ) {
        $subscribers = [];
        
        $query = $this->_pdo->prepare( 'SELECT * FROM subscriber as sub 
                                        LEFT JOIN subscriber_state as stat ON sub.id_state = stat.id WHERE sub.id_state = :id_state' );
		$query->bindValue( ':id', $id_state );
		$query->execute();

		while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {
            $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            
			$subscribers[] = $subscriber;
		}

		return $subscribers;
    }

    /**
     * Return the idea of a state
     *
     * @param string $state
     * @return integer
     */
    function getSubscriberStateByValue( string $state ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM subscriber_state WHERE value = :state' );
		$query->bindValue( ':state', $state );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
			return $res->id;
		}

		return NULL;
    }
}

?>