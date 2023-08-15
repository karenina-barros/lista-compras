<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Traits\DeleteTrait;
use Livro\Traits\EditTrait;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\DatagridWrapper;

class LojaList extends Page
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
        $this->activeRecord = 'Loja';

        $this->datagrid = new DatagridWrapper( new Datagrid );
        $this->datagrid->setTitle( 'Lojas' );

        $loja = new DatagridColumn( 'nome', 'Loja', 'left', '90%' );
        
        $this->datagrid->addColumn( $loja );

        $this->datagrid->addAction( 'Editar', new Action( [ new LojaForm, 'onEdit' ] ), 'id', 'fas fa-edit' );
        $this->datagrid->addAction( 'Excluir', new Action( [ $this, 'onDelete' ] ), 'id', 'fas fa-trash-alt' );

        parent::add( $this->datagrid );
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

