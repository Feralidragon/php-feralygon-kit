<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Root\System\Exceptions;

use Feralygon\Kit\Root\System\Exception;
use Feralygon\Kit\Core\Utilities\Type as UType;

/**
 * Root system invalid command name exception class.
 * 
 * This exception is thrown from the system whenever a given command name is invalid.
 * 
 * @since 1.0.0
 * @property-read string $name <p>The command name.</p>
 */
class InvalidCommandName extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Invalid command name {{name}}.\n" . 
			"HINT: Only alphanumeric ASCII characters (a-z, A-Z and 0-9) and underscore (_) are allowed, " . 
			"however the first character cannot be a number (0-9).";
	}
	
	
	
	//Implemented public static methods
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return ['name'];
	}
	
	
	
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function evaluateProperty(string $name, &$value) : ?bool
	{
		switch ($name) {
			case 'name':
				return UType::evaluateString($value);
		}
		return null;
	}
}
