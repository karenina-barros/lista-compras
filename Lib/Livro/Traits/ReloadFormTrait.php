<?php

namespace Livro\Traits;

use Exception;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

trait ReloadFormTrait
{
    public function onReload()
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

