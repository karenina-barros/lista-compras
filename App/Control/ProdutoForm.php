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

class ProdutoForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;

    use EditTrait;

    public function __construct()
    {
        parent::__construct();
        $this->connection = 'config';
        $this->activeRecord = 'Produto';

        $this->form = new FormWrapper( new Form( 'produto_form' ) );
        $this->form->setTitle( 'Formulário de Produto' );

        $id = new Hidden( 'id' );
        $nome = new Entry( 'nome' );

        $this->form->addField( '', $id, '100%' );
        $this->form->addField( 'Nome', $nome, '100%' );

        $this->form->addAction( 'Salvar', new Action( [ $this, 'onSave' ] ) );

        parent::add( $this->form );
    }

    public function onSave()
    {
        try{
            Transaction::open( 'config' );
            $dados = $this->form->getData();
            $this->form->setData( $dados );

            $produto = new Produto;
            $produto->fromArray( (array) $dados );
            $produto->store();

            Transaction::close();
            new Message( 'info', 'dados armazenados com sucesso!' );
        }
        catch( Exception $e ){
            new Message( 'error', $e->getMessage() );
            Transaction::rollback();
        }
    }

}

