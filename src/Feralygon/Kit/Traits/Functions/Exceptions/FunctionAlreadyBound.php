<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Traits\Functions\Exceptions;

use Feralygon\Kit\Traits\Functions\Exception;

/**
 * This exception is thrown from an object using the functions trait whenever a given function has already been bound.
 * 
 * @since 1.0.0
 * @property-read string $name
 * <p>The name.</p>
 */
class FunctionAlreadyBound extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Function {{name}} has already been bound in object {{object}}.";
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function loadProperties() : void
	{
		//parent
		parent::loadProperties();
		
		//properties
		$this->addProperty('name')->setAsString()->setAsRequired();
	}
}
