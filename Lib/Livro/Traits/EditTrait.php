<?php

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Exception;

trait EditTrait
{
	public function onEdit( $param )
	{
		try{
			if( isset( $param[ 'id' ] ) ){
				$id = $param[ 'id' ];

				Transaction::open( $this->connection );

				$class = $this->activeRecord;
				$object = $class::find( $id );
				$this->form->setData( $object );

				Transaction::close();
			}
		}
		catch( Exception $e ){
			new Message( 'eror', $e->getMessage() );
			Transaction::rollback();
		}
	}
}

