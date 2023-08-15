<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\DatagridWrapper;

class UnidadesMedidaList extends Page
{
    private $datagrid;
    private $connection;
    private $activeRecord;
    private $loaded;

    use DeleteTrait;
    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'config';
        $this->activeRecord = 'UnidadesMedida';

        $this->datagrid = new DatagridWrapper( new Datagrid );
        $this->datagrid->setTitle( 'Unidades de Medida' );

        $nome = new DatagridColumn( 'nome', 'Nome', 'left', '90%' );

        $this->datagrid->addColumn( $nome );
        $this->datagrid->addAction( 'Editar', new Action( [ new UnidadesMedidaForm, 'onEdit' ] ), 'id', 'fas fa-edit' );
        $this->datagrid->addAction( 'Excluir', new Action( [ $this, 'onDelete' ] ), 'id', 'fas fa-trash-alt' );

        $box = new VBox;
        $box->style = 'display:block';
        $box->add( $this->datagrid );

        parent::add( $box );
    }

    public function onReload()
    {
        $this->onReloadTrait();
        $this->loaded = true;
    }

    public function show()
    {
        if( !$this->loaded ){
            $this->onReload();
        }
        parent::show();
    }
}

