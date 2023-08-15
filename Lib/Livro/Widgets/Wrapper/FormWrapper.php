<?php

namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Container\Panel;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Base\Element;

class FormWrapper
{
	private $decorated;

	public function __construct( Form $form )
	{
		$this->decorated = $form;
	}

	public function __call( $method, $parameters )
	{
		return call_user_func_array( array( $this->decorated, $method ), $parameters );
	}

	public function show()
	{
		$element = new Element( 'form' );
		$element->enctype = "multipart/form-data";
		$element->method = 'post';
		$element->name = $this->decorated->getName();
		$element->width = '100%';

		foreach( $this->decorated->getFields() as $field ){
			$group = new Element( 'div' );
			$group->class = 'mb-3 row';

			$label = new Element( 'label' );
			$label->class = 'col-form-label col-sm-2';
			$label->add( $field->getLabel() );

			$col = new Element( 'div' );
			$col->class = 'col-sm-8';
			$col->add( $field );
			$field->class = 'form-control form-control-sm';

			$group->add( $label );
			$group->add( $col );
			$element->add( $group );
		}

		$group = new Element( 'div' );
		$i = 0;

		foreach( $this->decorated->getActions() as $label => $action ){
			$name = strtolower( str_replace( ' ', '_', $label ) );
			$button = new Button( $name );
			$button->setFormName( $this->decorated->getName() );
			$button->setAction( $action, $label );
			$button->class = 'btn btn-sm' . ( ( $i == 0 ) ? ' btn-success' : ' btn-primary' . ' m-1' );

			$group->add( $button );
			$i++;
		}

		$panel = new Panel( $this->decorated->getTitle() );
		$panel->add( $element );
		$panel->addFooter( $group );
		$panel->show();
	}
}

