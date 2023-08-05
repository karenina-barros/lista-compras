<?php

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Exception;

trait SaveTrait
{
	
	public function onSave()
	{
		try{
			Transaction::open( $this->connection );
			
			$class = $this->activeRecord;
			$dados =  $this->form->getData();

			$object = new $class;
			$object->fromArray( (array) $dados  );
			$object->store();

			Transaction::close();
			new Message( 'info', 'Dados armazenados com sucesso!' );
		}
		catch( Exception $e ){
			new Message( 'erro', $e->getMessage() );
		}
	}
}

