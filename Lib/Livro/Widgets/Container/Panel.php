<?php

namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class Panel extends Element
{
	private $body;
	private $footer;

	public function __construct( $panel_title = null )
	{
		parent::__construct( 'div' );
		$this->class = 'card mb-3';

		if( $panel_title ){
			$head = new Element( 'div' );
			$head->class = 'card-header ph-0 py-1';

			$label = new Element( 'h4' );
			$label->add( $panel_title );

			$title = new Element( 'div' );
			$title->class = 'card-title';
			$title->add( $label );
			$head->add( $title );
			parent::add( $head );
		}

		$this->body = new Element( 'div' );
		$this->body->class = 'card-body';
		parent::add( $this->body );

		$this->footer = new Element( 'div' );
		$this->footer->{'class'} = 'card-footer ph-0 py-1';
	}

	public function add( $content )
	{
		$this->body->add( $content );
	}

	public function addFooter( $footer )
	{
		$this->footer->add( $footer );
		parent::add( $this->footer );
	}
}

