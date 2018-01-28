<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Traits\ExtendedProperties\Exceptions;

use Feralygon\Kit\Core\Traits\ExtendedProperties\Exception;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Core extended properties trait cannot get property exception class.
 * 
 * This exception is thrown from an object using the extended properties trait whenever a given property 
 * with a given name is attempted to be retrieved.
 * 
 * @since 1.0.0
 * @property-read string $name <p>The property name.</p>
 */
class CannotGetProperty extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Cannot get property {{name}} from object {{object}}.";
	}
	
	
	
	//Overridden public static methods
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return array_merge(parent::getRequiredPropertyNames(), ['name']);
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function evaluateProperty(string $name, &$value) : ?bool
	{
		switch ($name) {
			case 'name':
				return UType::evaluateString($value) && UText::isIdentifier($value);
		}
		return parent::evaluateProperty($name, $value);
	}
}
