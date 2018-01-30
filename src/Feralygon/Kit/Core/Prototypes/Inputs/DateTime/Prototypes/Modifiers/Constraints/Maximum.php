<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Prototypes\Inputs\DateTime\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifiers\Constraints;
use Feralygon\Kit\Core\Options\Text as TextOptions;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Time as UTime
};

/**
 * Core date and time input maximum constraint modifier prototype class.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Core\Prototypes\Inputs\DateTime
 */
class Maximum extends Constraints\Maximum
{
	//Overridden public methods
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		return UText::localize("Maximum allowed date and time", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		$value_string = $this->stringifyValue($this->value, $text_options);
		if ($this->exclusive) {
			/**
			 * @placeholder value The maximum allowed value.
			 * @example Only a date and time before 2017-01-17 17:20:00 is allowed.
			 */
			return UText::localize(
				"Only a date and time before {{value}} is allowed.",
				self::class, $text_options, [
					'parameters' => ['value' => $value_string]
				]
			);
		}
		/**
		 * @placeholder value The maximum allowed value.
		 * @example Only a date and time before or on 2017-01-17 17:20:00 is allowed.
		 */
		return UText::localize(
			"Only a date and time before or on {{value}} is allowed.",
			self::class, $text_options, [
				'parameters' => ['value' => $value_string]
			]
		);
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function evaluateValue(&$value) : bool
	{
		return UTime::evaluateDateTime($value);
	}
	
	/** {@inheritdoc} */
	protected function stringifyValue($value, TextOptions $text_options) : string
	{
		return UTime::stringifyDateTime($value, $text_options);
	}
}