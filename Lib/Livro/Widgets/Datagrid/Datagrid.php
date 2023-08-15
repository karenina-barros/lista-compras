<?php 

namespace Livro\Widgets\Datagrid;

use Livro\Control\ActionInterface;

class Datagrid
{
	private $columns;
	private $items;
	private $actions;
    private $title;

	public function addColumn( DatagridColumn $object )
	{
		$this->columns[] = $object;
	}

	public function addAction( $label, ActionInterface $action, $field, $image = null )
	{
		$this->actions[] = [ 'label' => $label, 'action' => $action, 'field' => $field, 'image' => $image ];
	}

	public function addItem( $object )
	{
		$this->items[] = $object;

		foreach( $this->columns as $column ){
			$name = $column->getName();
			if( !isset( $object->$name ) ){
				$object->$name;
			}
		}
	}

    public function setTitle( $title )
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

	public function getItems()
	{
		return $this->items;
	}

	public function getColumns()
	{
	 return $this->columns;
	}

	public function getActions()
	{
		return $this->actions;
	}

	public function clear()
	{
		$this->items = [];
	}
	}

