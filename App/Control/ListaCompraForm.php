<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Field;

class ListaCompraForm extends Page
{
    private $form;
    
    public function __construct()
    {
        parent::__construct();
        $this->form = new FormWrapper( new Form( 'lista_compra' ) );
        $this->form->setTitle( 'Lista de Compra' );

        $loja = new Combo( 'loja' );
        $produto = new Combo( 'produto' );
        $unidadeMedida = new Combo( 'unidade_medida' );
        $valor = new Entry( 'valor' );

        Transaction::open( 'config' );
        $lojas = Loja::all();
        $items = array();

        foreach( $lojas as $l ){
            $items[ $l->id ] = $l->nome;
        }
        
        $loja->addItems( $items );

        $produtos = Produto::all();
        $items = array();

        foreach( $produtos as $p ){
            $items[ $p->id ] = $p->nome;
        }

        $produto->addItems( $items );

        $unidadesMedidas = UnidadesMedida::all();
        $items = array();

        foreach( $unidadesMedidas as $u ){
            $items[ $u->id ] = $u->nome;
        }

        $unidadeMedida->addItems( $items );

        $this->form->addField( 'Loja', $loja, '100%' );
        $this->form->addField( 'Produto', $produto, '30%' );
        $this->form->addField( 'Unidade Medida', $unidadeMedida, '30%' );
        $this->form->addField( 'Valor', $valor, '30%' );
        $this->form->addAction( 'Salva', new Action( [ $this, 'onSave' ] ) );

        parent::add( $this->form );
    }

}

