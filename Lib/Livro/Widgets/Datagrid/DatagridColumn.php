<?php

namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridColumn 
{
	private $name;
	private $label;
	private $aling;
	private $width;
	private $action;
	private $transformer;

	public function __construct( $name, $label, $aling, $width )
	{
		$this->name = $name;
		$this->label = $label;
		$this->aling = $aling;
		$this->width = $width;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLabel()
	{
		 return $this->label;
	}

	public function getAling()
	{
		return $this->aling;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function setAction( Action $action )
	{
		$this->action = $action;
	}

	public function getAction()
	{
		if( $this->action ){
			return $this->action->serialize();
		}	
	}

	public function setTransformer( $callback )
	{
		$this->transformer = $callback;
	}

	public function getTransformer()
	{
		return $this->transformer;
	}
}

