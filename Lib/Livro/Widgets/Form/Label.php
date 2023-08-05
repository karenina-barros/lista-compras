<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class Label extends Field implements FormElementInterface
{
	public function __construct( $value )
	{
		$this->setValue( $value );
		$this->tag = new Element( 'label' );
		$this->tag->for = $value;
		$this->tag->class = 'me-3 ms-1';
	}

	public function add( $child )
	{
		$this->tag->add( $child );
	}

	public function show()
	{
		$this->tag->add( $this->value );
		$this->tag->show();
	}
}

