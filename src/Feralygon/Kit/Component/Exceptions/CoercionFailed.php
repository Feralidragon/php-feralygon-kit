<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Component\Exceptions;

use Feralygon\Kit\Component\Exception;
use Feralygon\Kit\Interfaces\Throwables\Coercive as ICoercive;
use Feralygon\Kit\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * This exception is thrown from a component whenever a coercion has failed with a given value.
 * 
 * @since 1.0.0
 * @property-read mixed $value
 * <p>The value.</p>
 * @property-read string|null $error_code [default = null]
 * <p>The error code.</p>
 * @property-read string|null $error_message [default = null]
 * <p>The error message.</p>
 */
class CoercionFailed extends Exception implements ICoercive
{
	//Public constants
	/** Invalid type error code. */
	public const ERROR_CODE_INVALID_TYPE = 'INVALID_TYPE';
	
	/** Build exception error code. */
	public const ERROR_CODE_BUILD_EXCEPTION = 'BUILD_EXCEPTION';
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return $this->isset('error_message')
			? "Coercion failed with value {{value}} using component {{component}}, " . 
				"with the following error: {{error_message}}"
			: "Coercion failed with value {{value}} using component {{component}}.";
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Interfaces\Throwables\Coercive)
	/** {@inheritdoc} */
	public function getValue()
	{
		return $this->get('value');
	}
	
	/** {@inheritdoc} */
	public function getErrorCode() : ?string
	{
		return $this->get('error_code');
	}
	
	/** {@inheritdoc} */
	public function getErrorMessage() : ?string
	{
		return $this->get('error_message');
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		//parent
		parent::buildProperties();
		
		//properties
		$this->addProperty('value')->setAsRequired();
		$this->addProperty('error_code')
			->setEvaluator(function (&$value) : bool {
				return !isset($value) || (UType::evaluateString($value) && in_array($value, [
					self::ERROR_CODE_INVALID_TYPE,
					self::ERROR_CODE_BUILD_EXCEPTION
				], true));
			})
			->setDefaultValue(null)
		;
		$this->addProperty('error_message')->setAsString(false, true)->setDefaultValue(null);
	}
	
	/** {@inheritdoc} */
	protected function getPlaceholderValueString(string $placeholder, $value) : string
	{
		if ($placeholder === 'error_message' && is_string($value)) {
			return UText::uncapitalize($value, true);
		}
		return parent::getPlaceholderValueString($placeholder, $value);
	}
}
