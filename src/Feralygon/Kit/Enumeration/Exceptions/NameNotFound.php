<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Enumeration\Exceptions;

use Feralygon\Kit\Enumeration\Exception;

/**
 * Enumeration name not found exception class.
 * 
 * This exception is thrown from an enumeration whenever a given name is not found.
 * 
 * @since 1.0.0
 * @property-read string $name <p>The name.</p>
 */
class NameNotFound extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Name {{name}} not found in enumeration {{enumeration}}.";
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		//parent
		parent::buildProperties();
		
		//properties
		$this->addProperty('name')->setAsString()->setAsRequired();
	}
}