<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class CheckButton extends Field implements FormElementInterface
{
	public function show()
	{
		$tag = new Element( 'input' );
		$tag->class = 'form-check-input';
		$tag->name = $this->name;
		$tag->value = $this->value;
		$tag->id = $this->getLabel();
		$tag->type = 'checkbox';

		if( !parent::getEditable() ){
			$tag->readonly = "1";
		}

		if( $this->properties ){
			foreach( $this->properties as $property => $value ){
				$tag->$property = $value;
			}
		}

		$tag->show();
	}
}

