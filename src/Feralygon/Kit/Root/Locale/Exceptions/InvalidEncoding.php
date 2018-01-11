<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Root\Locale\Exceptions;

use Feralygon\Kit\Root\Locale\Exception;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Root locale invalid encoding exception class.
 * 
 * This exception is thrown from the locale whenever a given encoding is invalid.
 * 
 * @since 1.0.0
 * @property-read string $encoding <p>The encoding.</p>
 * @property-read string[] $encodings [default = []] <p>The allowed encodings.</p>
 */
class InvalidEncoding extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		$message = "Invalid encoding {{encoding}}.";
		if (!empty($this->get('encodings'))) {
			$message .= "\n" . 
				"HINT: Only the following encodings are allowed: {{encodings}}.";
		}
		return $message;
	}
	
	
	
	//Implemented public static methods
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return ['encoding'];
	}
	
	
	
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function evaluateProperty(string $name, &$value) : ?bool
	{
		switch ($name) {
			case 'encoding':
				return UType::evaluateString($value);
			case 'encodings':
				if (is_array($value)) {
					$value = array_values($value);
					foreach ($value as &$v) {
						if (!UType::evaluateString($v)) {
							return false;
						}
					}
					unset($v);
					return true;
				}
				return false;
		}
		return null;
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function getPlaceholderValueString(string $placeholder, $value) : string
	{
		if ($placeholder === 'encodings') {
			return UText::stringify($value, null, ['flags' => UText::STRING_NONASSOC_CONJUNCTION_AND]);
		}
		return parent::getPlaceholderValueString($placeholder, $value);
	}
}
