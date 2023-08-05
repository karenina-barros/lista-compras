<?php

namespace Livro\Database;

interface RecordInterface
{
	public function fromArray( $data );

	public function store();

	public function delete( $id = NULL );

	public static function all();

	public static function find( $id );

	public function prepare( $data );

	public function escape( $value );
}	
