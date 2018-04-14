<?php

namespace Model;

use \PDO;

/**
 * Class FieldMapper interact with the BDD to return Field
 */
class FieldMapper { 
    /**
     * PDO Object to interact with the BDD
     *
     * @var PDO
     */
    protected $_pdo;

    function __construct( PDO $pdo ) {
        $this->_pdo = $pdo;
    }
    
    /**
     * Save / update a field
     *
     * @param integer $id_subscriber
     * @param string $title
     * @param integer $id_type
     * @param integer [opt] $id
     * @return void
     */
    public function saveField( int $id_subscriber, string $title, int $id_type, int $id = 0 ) {
        
        // If no ID Given let's create a field
        if ( $id == 0 ) {
            $query = $this->_pdo->prepare( 'INSERT INTO field (id_subscriber, id_type, title) VALUES (:id_subscriber, :id_type, :title)' );
        } else {
            $query = $this->_pdo->prepare( 'UPDATE field SET id_subscriber=:id_subscriber, id_type=:id_type, title=:title WHERE id=:id' );
            $query->bindValue( ':id', $id );
        }

        $query->bindValue( ':id_subscriber', $id_subscriber );
        $query->bindValue( ':id_type', $id_type );
        $query->bindValue( ':title', $title );
        $query->execute();
    }

    /**
     * Return all field with type and name of the owner
     *
     * @return void
     */
    public function getAllField() {
        $fields = [];
        
        $query = $this->_pdo->prepare( 'SELECT * FROM field_data ORDER BY field_data.id_subscriber' );
        $query->execute();
        
        while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {
            $field = [
                'id'            => $res->id, 
                'title'         => $res->title, 
                'id_type'       => $res->id_type, 
                'type'          => $res->type, 
                'id_subscriber' => $res->id_subscriber, 
                'subscriber'    => $res->subscriber,
            ];

			$fields[] = $field;
		}

		return $fields;
    }

    /**
     * Return a specif field with its id
     *
     * @param integer $id
     * @return NULL|array
     */
    public function getFieldById( int $id ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM field_data WHERE id = :id' );
		$query->bindValue( ':id', $id );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
            $field = [
                'id'            => $res->id, 
                'title'         => $res->title, 
                'id_type'       => $res->id_type, 
                'type'          => $res->type, 
                'id_subscriber' => $res->id_subscriber, 
                'subscriber'    => $res->subscriber,
            ];

			return $field;
		}

		return NULL;
    }

    /**
     * Return all field of a subscriber
     *
     * @param integer $id
     * @return NULL|array
     */
    public function getFieldBySubscriberId( int $id_subscriber ) {
        $query = $this->_pdo->prepare( 'SELECT * FROM field_data WHERE id_subscriber = :id_subscriber' );
		$query->bindValue( ':id_subscriber', $id_subscriber );
		$query->execute();
		$res = $query->fetch( PDO::FETCH_OBJ );

		if( !empty( $res ) ) {
            $field = [
                'id'            => $res->id, 
                'title'         => $res->title, 
                'id_type'       => $res->id_type, 
                'type'          => $res->type, 
                'id_subscriber' => $res->id_subscriber, 
                'subscriber'    => $res->subscriber,
            ];

			return $field;
		}

		return NULL;
    }

    /**
     * Return all field of a specific type, can be complete by a subscriber id
     *
     * @param integer $id_type
     * @param integer $id_subscriber
     * @return NULL|array
     * @todo : Better way to do this IF... 
     */
    public function getFieldFiltered( int $id_type, int $id_subscriber = 0 ) {
        $fields = array();

        $query_string = 'SELECT * FROM field_data WHERE id_type = :id_type';
        if ( $id_subscriber != 0 ) {
            $query_string .= ' AND id_subscriber = :id_subscriber';
        }

        $query = $this->_pdo->prepare( $query_string );
        $query->bindValue( ':id_type', $id_type );
        
        if ( $id_subscriber != 0 ) { 
            $query->bindValue( ':id_subscriber', $id_subscriber );  
        }

        $query->execute();

        while( $res = $query->fetch( PDO::FETCH_OBJ ) ) {   
            $field = [
                'id'            => $res->id, 
                'title'         => $res->title, 
                'id_type'       => $res->id_type, 
                'type'          => $res->type, 
                'id_subscriber' => $res->id_subscriber, 
                'subscriber'    => $res->subscriber,
            ];

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Delete a specific field
     *
     * @param integer $id
     * @return void
     */
    public function deleteField( int $id ) {
        $query = $this->_pdo->prepare("DELETE FROM field WHERE id = :id");
        $query->bindValue( ':id', $id );
		$query->execute();
    }

    /**
     * Check if it's a good id for a type.
     *
     * @param integer $id_type
     * @return boolean
     */
    public function checkTypeById( int $id_type ) {
        $return = true;

        $query = $this->_pdo->prepare( 'SELECT * FROM field_type WHERE id = :id_type' );
        $query->bindValue( ':id_type', $id_type );
        $query->execute(); 
        
        $res = $query->fetch( PDO::FETCH_OBJ );

        // If we get no responds, it's not a good id.
        if ( empty( $res ) ) {
            $return = false;
        }

        return $return;
    }
}