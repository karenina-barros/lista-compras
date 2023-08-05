<?php

namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Base\Element;

class DatagridWrapper
{
	private $decorated;

	public function __construct( Datagrid $datagrid )
	{
		$this->decorated = $datagrid;
	}

	public function __call( $method, $parameters )
	{
		return call_user_func_array( array( $this->decorated, $method ), $parameters );
	}

	public function __set( $atribute, $value )
	{
		$this->decorated->$atribute = $value;
	}

	public function createHeaders( $thead )
	{
		$row = new Element( 'tr' );
		$thead->add( $row );

		$actions = $this->decorated->getActions();
		$columns = $this->decorated->getColumns();

		if( $actions ){
			foreach( $actions as $action ){
				$celula = new Element( 'th' );
				$celula->width = '40px';
				$row->add( $celula );
			}
		}

		if( $columns ){
			foreach( $columns as $column ){
				$label = $column->getLabel();
				$aling = $column->getAling();
				$width = $column->getWidth();

				$celula = new Element( 'th' );
				$celula->add( $label );
				$celula->style = "text-aling:$aling";
				$celula->width = $width;
				$row->add( $celula );

				if( $column->getAction() ){
					$url = $column->getAction();
					$celula->onclick = "document.location='$url'";
				}
			}
		}
	}

	public function show()
	{
		$element = new Element( 'table' );
		$element->class = 'table table-striped table-houver table-sm';

		$thead = new Element( 'thead' );
		$element->add( $thead );
		$this->createHeaders( $thead );

		$tbody = new Element( 'tbody' );
		$element->add( $tbody );
		
		$items = $this->decorated->getItems();
		if( !empty( $items ) ){
			foreach( $items as $item ){
				$this->createItem( $tbody, $item );
			}
		}

		$panel = new Panel;
		$panel->type = 'datagrid';
		$panel->add( $element );
		$panel->show();
	}

	public function createItem( $tbody, $item )
	{
		$row = new Element( 'tr' );
		$tbody->add( $row );

		$actions = $this->decorated->getActions();
		$columns = $this->decorated->getColumns();

		if( $actions ){
			foreach( $actions as $action ){
				$url   = $action[ 'action' ]->serialize();
				$label = $action[ 'label' ];
				$image = $action[ 'image' ];
				$field = $action[ 'field' ];

				$key = $item->$field;

				$link = new Element( 'a' );
				$link->href = "{$url}&key={$key}&{$field}={$key}";

				if( $image ){
					$i = new Element( 'i' );
					$i->class = $image;
					$i->title = $label;
					$i->style = "width: 20px";
					$i->add( '' );
					$link->add( $i );
				
				}
				else{
					$link->add( $label );
				}

				$element = new Element( 'td' );
				$element->add( $link );
				$element->aling = 'center';

				$row->add( $element );

			}
		}

		if( $columns ){
			foreach( $columns as $column ){
				$name = $column->getName();
				$aling = $column->getAling();
				$width = $column->getWidth();
				$function = $column->getTransformer();
				$data = $item->$name;

				if( $function ){
					$data = call_user_func( $function, $data );
				}

				$element = new Element( 'td' );
				$element->add( $data );
				$element->aling = $aling;
				$element->width = $width;

				$row->add( $element );
			}
		}
	}
}

