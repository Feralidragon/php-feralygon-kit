<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Prototypes\Inputs\Timestamp\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifiers\Constraints;
use Feralygon\Kit\Core\Options\Text as TextOptions;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Time as UTime
};

/**
 * Core timestamp input minimum constraint modifier prototype class.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Core\Prototypes\Inputs\Timestamp
 */
class Minimum extends Constraints\Minimum
{
	//Overridden public methods
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		return UText::localize("Minimum allowed timestamp", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		$value_string = $this->stringifyValue($this->value, $text_options);
		if ($this->exclusive) {
			/**
			 * @placeholder value The minimum allowed value.
			 * @example Only a timestamp after 2017-01-15 12:45:00 is allowed.
			 */
			return UText::localize("Only a timestamp after {{value}} is allowed.", self::class, $text_options, ['parameters' => ['value' => $value_string]]);
		}
		/**
		 * @placeholder value The minimum allowed value.
		 * @example Only a timestamp after or on 2017-01-15 12:45:00 is allowed.
		 */
		return UText::localize("Only a timestamp after or on {{value}} is allowed.", self::class, $text_options, ['parameters' => ['value' => $value_string]]);
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function evaluateValue(&$value) : bool
	{
		return UTime::evaluateTimestamp($value);
	}
	
	/** {@inheritdoc} */
	protected function stringifyValue($value, TextOptions $text_options) : string
	{
		return UTime::stringifyTimestamp($value, $text_options);
	}
}
