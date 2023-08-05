<?php 

namespace Livro\Traits;

use Livro\Control\Action;
use Livro\ Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Exception;

trait DeleteTrait
{
	public function onDelete( $param )
	{
		$id = $param[ 'id' ];
		$action1 = new Action( [ $this, 'Delete' ] );
		$action1->setParameter( 'id', $id );
		new Question( 'Deseja realmente excluir o registro?', $action1 );
	}

	public function Delete( $param )
	{
		try{
			$id = $param[ 'id' ];

			Transaction::open( $this->connection );

			$class = $this->activeRecord;
			$object = $class::find( $id );
			$object->delete();

			Transaction::close();
			$this->onReload();
			new Message( 'info', "Registro excluído com sucesso!" );
		}
		catch( Exception $e ){
			new Message( 'eror', $e->getMessage() );
		}
	}
}

