<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Root\Locale\Exceptions;

use Feralygon\Kit\Root\Locale\Exception;
use Feralygon\Kit\Core\Interfaces\Throwables\Coercion as ICoercion;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Root locale language coercion failed exception class.
 * 
 * This exception is thrown from the locale whenever the coercion into a language has failed with a given value.
 * 
 * @since 1.0.0
 * @property-read mixed $value <p>The value.</p>
 * @property-read string|null $error_code [default = null] <p>The error code.</p>
 * @property-read string|null $error_message [default = null] <p>The error message.</p>
 */
class LanguageCoercionFailed extends Exception implements ICoercion
{
	//Public constants
	/** Null error code. */
	public const ERROR_CODE_NULL = 'NULL';
	
	/** Invalid type error code. */
	public const ERROR_CODE_INVALID_TYPE = 'INVALID_TYPE';
	
	/** Invalid error code. */
	public const ERROR_CODE_INVALID = 'INVALID';
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return $this->isset('error_message')
			? "Language coercion failed with value {{value}}, with the following error: {{error_message}}"
			: "Language coercion failed with value {{value}}.";
	}
	
	
	
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		$this->addProperty('value')->setAsRequired();
		$this->addProperty('error_code')
			->setEvaluator(function (&$value) : bool {
				return !isset($value) || (UType::evaluateString($value) && in_array($value, [
					self::ERROR_CODE_NULL,
					self::ERROR_CODE_INVALID_TYPE,
					self::ERROR_CODE_INVALID
				], true));
			})
			->setDefaultValue(null)
		;
		$this->addProperty('error_message')->setAsString(false, true)->setDefaultValue(null);
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function getPlaceholderValueString(string $placeholder, $value) : string
	{
		if ($placeholder === 'error_message' && is_string($value)) {
			return UText::uncapitalize($value, true);
		}
		return parent::getPlaceholderValueString($placeholder, $value);
	}
}
