<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Traits\Properties\Exceptions;

use Feralygon\Kit\Core\Traits\Properties\Exception;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Core properties trait missing required properties exception class.
 * 
 * This exception is thrown from an object using the properties trait whenever required properties are missing.
 * 
 * @since 1.0.0
 * @property-read string[] $names <p>The property names.</p>
 */
class MissingRequiredProperties extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return count($this->get('names')) === 1
			? "Missing required property {{names}} for object {{object}}."
			: "Missing required properties {{names}} for object {{object}}.";
	}
	
	
	
	//Overridden public static methods
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return array_merge(parent::getRequiredPropertyNames(), ['names']);
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function evaluateProperty(string $name, &$value) : ?bool
	{
		switch ($name) {
			case 'names':
				if (is_array($value) && !empty($value)) {
					$value = array_values($value);
					foreach ($value as &$v) {
						if (!UType::evaluateString($v) || !UText::isIdentifier($v)) {
							return false;
						}
					}
					return true;
				}
				return false;
		}
		return parent::evaluateProperty($name, $value);
	}
	
	/** {@inheritdoc} */
	protected function getPlaceholderValueString(string $placeholder, $value) : string
	{
		if ($placeholder === 'names') {
			return UText::stringify($value, null, ['flags' => UText::STRING_NONASSOC_CONJUNCTION_AND]);
		}
		return parent::getPlaceholderValueString($placeholder, $value);
	}
}
