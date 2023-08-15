<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\DatagridWrapper;

class ListaCompraList extends Page
{
    /**
     * @var $datagrid Livro\Widgets\Wrapper\DatagridWrapper
     */
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        $this->datagrid = new DatagridWrapper( new Datagrid );
        $this->datagrid->setTitle( 'Minha Lista' );

        $codigo = new DatagridColumn( 'codigo', 'Código', 'center', '20%' );
        $loja = new DatagridColumn( 'loja', 'Loja', 'left', '30%' );
        $data = new DatagridColumn( 'data', 'Data', 'left', '30%' );

        $this->datagrid->addColumn( $codigo );
        $this->datagrid->addColumn( $loja );
        $this->datagrid->addColumn( $data );

        
        $this->datagrid->addAction( 'Editar', new Action( [ $this, 'onEdit' ] ), 'id', 'fas fa-edit' );
        $this->datagrid->addAction( 'Excluir', new Action( [ $this, 'onDelete' ] ), 'id', 'fas fa-trash-alt' );

        $VBox = new VBox;
        $VBox->style = 'display:block';
        $VBox->add($this->datagrid);

        parent::add($VBox);
    }

}

