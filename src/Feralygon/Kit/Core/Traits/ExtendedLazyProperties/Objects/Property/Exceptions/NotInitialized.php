<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Traits\ExtendedLazyProperties\Objects\Property\Exceptions;

use Feralygon\Kit\Core\Traits\ExtendedLazyProperties\Objects\Property\Exception;

/**
 * Core extended lazy properties trait property object not initialized exception class.
 * 
 * This exception is thrown from an extended lazy properties trait property object whenever it 
 * has not been initialized yet.
 * 
 * @since 1.0.0
 */
class NotInitialized extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Property {{property}} has not been initialized yet.\n" . 
			"HINT: A property must be initialized first through the \"setValue\", " . 
			"\"setGetter\" or \"setSetter\" method.";
	}
}