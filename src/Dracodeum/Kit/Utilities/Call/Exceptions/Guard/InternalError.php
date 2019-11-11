<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Utilities\Call\Exceptions\Guard;

use Dracodeum\Kit\Utilities\Call\Exceptions\Guard as Exception;

/**
 * This exception is thrown from the call utility <code>guardInternal</code> method whenever an internal error occurs 
 * in a given function or method call.
 */
class InternalError extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage(): string
	{
		//message
		$message = $this->isset('object_class')
			? "Internal error occurred in method call {{function_name}} in {{object_class}}."
			: "Internal error occurred in function call {{function_name}}.";
		
		//error message
		if ($this->isset('error_message')) {
			$message .= "\nERROR: {{error_message}}";
		}
		
		//hint message
		if ($this->isset('hint_message')) {
			$message .= "\nHINT: {{hint_message}}";
		}
		
		//return
		return $message;
	}
}