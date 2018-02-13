<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Components\Input\Exceptions;

use Feralygon\Kit\Core\Components\Input\Exception;

/**
 * Core input component invalid modifier exception class.
 * 
 * This exception is thrown from an input whenever a given modifier is invalid.
 * 
 * @since 1.0.0
 * @property-read mixed $modifier <p>The modifier.</p>
 */
class InvalidModifier extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Invalid modifier {{modifier}} in input {{component}} (with prototype {{prototype}}).\n" . 
			"HINT: Only a modifier instance or name is allowed.";
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		//parent
		parent::buildProperties();
		
		//properties
		$this->addProperty('modifier')->setAsRequired();
	}
}
