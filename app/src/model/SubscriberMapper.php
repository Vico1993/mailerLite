<?php

namespace Model;

use \PDO;

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
     * Save / update a subscriber
     *
     * @param string $name
     * @param string $email
     * @param integer $id_state
     * @param integer [opt] $id
     * @return void
     */
    public function saveSubscriber( string $name, string $email, int $id_state, int $id = 0 ) {
        
        // If no ID given let's create this subscriber.
        if ( $id == 0 ) {
            $query = $this->_pdo->prepare( 'INSERT INTO subscriber (name, email, id_state) VALUES (:name, :email, :id_state)' );
        } else {
            $query = $this->_pdo->prepare( 'UPDATE subscriber SET name=:name, email=:email, id_state=:id_state WHERE id = :id' );
            $query->bindValue( ':id', $id );
        }

		$query->bindValue( ':name', $name );
        $query->bindValue( ':email', $email );
		$query->bindValue( ':id_state', $id_state );
        $query->execute();
    }

    /**
     * Return an array with all Subscriber in the Database.
     *
     * @return array 
     */
    public function getAllSubscribers() {
        $subscribers = [];
        
        $query = $this->_pdo->prepare( 'SELECT * FROM subs ORDER BY subs.state_value' );
		$query->execute();

		while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {
            // $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            $subscriber = [
                'id'        => $res->id, 
                'name'      => $res->name, 
                'email'     => $res->email, 
                'state'     => $res->state_value,
            ];

			$subscribers[] = $subscriber;
		}

		return $subscribers;
    }

    /**
     * Return a Subscriber with this id
     *
     * @param integer $id
     * @return NULL|array
     */
    public function getSubscriberById( int $id ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM subs WHERE id = :id' );
		$query->bindValue( ':id', $id );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
            // $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            $subscriber = [
                'id'        => $res->id, 
                'name'      => $res->name, 
                'email'     => $res->email, 
                'state'     => $res->state_value,
            ];

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
    public function getSubscriberByState( int $id_state ) {
        $subscribers = [];
        
        $query = $this->_pdo->prepare( 'SELECT sub.id, sub.name, sub.email, stat.value AS state_value FROM subscriber as sub 
                                        LEFT JOIN subscriber_state as stat ON sub.id_state = stat.id WHERE sub.id_state = :id_state' );
		$query->bindValue( ':id_state', $id_state );
		$query->execute();

		while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {
            // $subscriber = new Subscriber( $res->id, $res->name, $res->email, $res->state_value );
            $subscriber = [
                'id'        => $res->id, 
                'name'      => $res->name, 
                'email'     => $res->email, 
                'state'     => $res->state_value,
            ];

			$subscribers[] = $subscriber;
		}

		return $subscribers;
    }

    /**
     * Return the id of a state
     *
     * @param string $state
     * @return integer
     */
    public function getSubscriberStateByValue( string $state ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM subscriber_state WHERE value = :state' );
		$query->bindValue( ':state', $state );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
			return $res->id;
		}

		return NULL;
    }

    /**
     * Delete a Subscriber
     *
     * @param integer $id
     * @return void
     */
    public function deleteSubscriber( int $id ) {
        $query = $this->_pdo->prepare("DELETE FROM subscriber WHERE id = :id");
        $query->bindValue( ':id', $id );
		$query->execute();
    }

    /**
     * Check if we get a good id_state
     *
     * @param integer $id_state
     * @return boolean
     */
    public function checkStateById( int $id_state ) {
        $return = true;

        $query = $this->_pdo->prepare( 'SELECT * FROM subscriber_state WHERE id = :id_state' );
        $query->bindValue( ':id_state', $id_state );
        $query->execute(); 
        
        $res = $query->fetch( PDO::FETCH_OBJ );

        // If we get no responds, it's not a good id.
        if ( empty( $res ) ) {
            $return = false;
        }

        return $return;

    }
}

?>