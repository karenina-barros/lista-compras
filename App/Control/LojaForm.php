<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Traits\EditTrait;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Wrapper\FormWrapper;

class LojaForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;
    private $loaded;

    use EditTrait;

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'config';
        $this->activeRecord = 'Loja';

        $this->form = new FormWrapper( new Form( 'loja_form' ) );
        $this->form->setTitle( 'Formulário de Lojas' );

        $id = new Hidden( 'id' );
        $loja = new Entry( 'nome' );
        
        $this->form->addField( '', $id, '100%' );
        $this->form->addField( 'Loja', $loja, '100%' );
        $this->form->addAction( 'Salvar', new Action( [ $this, 'onSave' ] ) );

        parent::add( $this->form );
    }
    
    public function onSave()
    {
        try{
            Transaction::open( 'config' );
            $dados = $this->form->getData();
            $this->form->setData( $dados );

            $loja = new Loja;
            $loja->fromArray( (array) $dados );
            $loja->store();

            Transaction::close();
            new Message( 'info', 'dados armazenados com sucesso!' );
        }
        catch( Exception $e ){
            new Message( 'error', $e->getMessage() );
            Transaction::rollback();
        }
    }
}

