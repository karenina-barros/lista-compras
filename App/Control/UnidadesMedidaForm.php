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

class UnidadesMedidaForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;

    use EditTrait;

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'config';
        $this->activeRecord = 'UnidadesMedida';

        $this->form = new FormWrapper( new Form( 'unidades_medida_form' ) );
        $this->form->setTitle( 'Cadastro de Unidades de Medida' );

        $id = new Hidden( 'id' );
        $nome = new Entry( 'nome' );

        $this->form->addField( '', $id, '' );
        $this->form->addField( 'Nome', $nome, '100%' );
        $this->form->addAction( 'Salvar', neW Action( [$this, 'onSave'] ) );

        parent::add( $this->form );
    }

    public function onSave()
    {
        try{
            Transaction::open( 'config' );
            $dados = $this->form->getData();
            $this->form->setData( $dados );

            $unidadesMedida = new UnidadesMedida;
            $unidadesMedida->fromArray( (array) $dados );
            $unidadesMedida->store();

            Transaction::close();
            new Message( 'info', 'Dados armazenados com sucesso!' );
        }
        catch( Exception $e ){
            new Message( 'eror', $e->getMessage() );
            Transaction::rollback();
        }
    }
}

