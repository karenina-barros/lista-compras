<?php

namespace Livro\Database;

abstract class Record implements RecordInterface
{
	protected $data;

	public function __construct( $id = null )
	{
		if( $id ){
			$object = $this->load( $id );

			if( $object ){
				$this->fromArray( $object->toArray() );
			}
		}
	}

	public function __clone()
	{
		 unset( $this->data[ 'id' ] );
	}

	public function __set( $prop, $value )
	{
		if( method_exists( $this, 'set_'.$prop ) ){
			call_user_func( array( $this, 'set_'.$prop ), $value );
			return;
		}

		if( $value === null ){
			unset( $this->data[ $prop ] );
			return;
		}

		$this->data[ $prop ] = $value ;
	}

	public function __get( $prop )
	{
		if(  method_exists( $this, 'get_'.$prop ) ){
			return call_user_func( array( $this, 'get_'.$prop ) );
		}

		if( isset( $this->data[ $prop ] ) ){

			return $this->data[ $prop ];
		}
	}

	public function __isset( $prop )
	{
		return isset( $this->data[ $prop ] );
	}

	private function getEntity()
	{
		$class = get_class( $this );
		return constant( "{$class}::TABLENAME" );
	}

	public function fromArray( $data )
	{
		return $this->data = $data;
	}

	public function store()
	{
		$prepared = $this->prepare( $this->data );

		if( empty( $this->data[ 'id' ] ) || (  !$this->load( $this->id )) ){
		
			if( empty( $this->data[ 'id' ] ) ){
				$this->id = $this->getLast() + 1;
				$prepared[ 'id' ] = $this->id;
			}

			$sql = "INSERT INTO {$this->getEntity()} ".
					'(' . implode( ', ',  array_keys( $prepared ) ) . ' )'.
					' values ' .
					'(' . implode( ', ', array_values( $prepared ) ) . ' )';
		}
		else{
			$sql = "UPDATE {$this->getEntity()}";
			if( $prepared ){
				foreach( $prepared as $column => $value ){
					if( $column !== 'id' ){
						$set[] = "{$column} = {$value}";
					}
				}
			}
			$sql .= ' SET ' . implode( ', ', $set );
			$sql .= ' WHERE id=' . (int) $this->data[ 'id' ];
		}
		if( $conn = Transaction::get() ){
			Transaction::log($sql);
			$result = $conn->exec( $sql );
			return $result;
		}
		else{
			throw new Exception( 'Não há trasação ativa!!' );
		}
	}

	public function load( $id )
	{
		$sql = "SELECT * FROM {$this->getEntity()}";
		$sql .= " WHERE id=" . (int) $id;

		$conn = Transaction::get();
	
		if( empty( $conn ) ){
			throw new \Exception( "Não há trasação ativa" );
		}
		$result = $conn->query( $sql );
		
		Transaction::log( $sql );
		
		if( $result ){
			$object = $result->fetchObject( get_class( $this ) );
		}

		return $object;
	}

	public function delete( $id = NULL )
	{
		$id = $id ? $id : $this->id;
		$sql = "DELETE FROM {$this->getEntity()}";
		$sql .= '  WHERE id=' . (int) $this->data[ 'id' ];

		if( $conn = Transaction::get() ){
			Transaction::log( $sql );
			$result = $conn->exec( $sql );
			return $result;
		}
		else{
			throw new Exception( "Não há trasação ativa!!" );
		}
	}

	public function toArray()
	{
		return $this->data;
	}

	private function getLast()
	{
		if( $conn = Transaction::get() ){
			$sql = "SELECT max(id) FROM {$this->getEntity()}";
			Transaction::log( $sql );
			$result = $conn->query( $sql );

			$row = $result->fetch();
			return $row[0];
		}
		else{
			throw new Exception( "Não há trasação ativa!!" );
		}
	}

	public static function all()
	{
		$classname = get_called_class();
		$repo = new Repository( $classname );
		return $repo->load( new Criteria );
	}

	public static function find( $id )
	{
		$classname = get_called_class();
		$ar = new $classname;
		return $ar->load( $id );
	}

	public function prepare( $data )
	{
		$prepared = array();
		foreach( $data as $key => $value ){
			if( is_scalar( $value ) ){
				$prepared[ $key ] = $this->escape( $value );
			}
		}
		return $prepared;
	}

	public function escape( $value )
	{
		if( is_scalar( $value ) ){
			if( is_string( $value ) && ( !empty( $value ) ) ){
				$value = addslashes( $value );
				return "'$value'";
			}
			else if( is_bool( $value ) ){
				return $value ? 'TRUE' : 'FALSE';
			}
			else if( $value !== '' ){
				return $value;
			}
			else{
				return "NULL";
			}
		}	
	}
}

