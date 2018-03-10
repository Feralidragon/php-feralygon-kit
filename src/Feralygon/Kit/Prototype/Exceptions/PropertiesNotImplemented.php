<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototype\Exceptions;

use Feralygon\Kit\Prototype\Exception;

/**
 * This exception is thrown from a prototype whenever properties are given but there is nothing implemented 
 * to handle them.
 * 
 * @since 1.0.0
 */
class PropertiesNotImplemented extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Properties not implemented in prototype {{prototype}}.\n" . 
			"HINT: In order to use properties, the \"\\Feralygon\\Kit\\Prototype\\Interfaces\\Properties\" " . 
			"interface must be implemented by this prototype.";
	}
}
