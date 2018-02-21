<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Utilities\Text\Options;

use Feralygon\Kit\Options;
use Feralygon\Kit\Traits\LazyProperties\Objects\Property;
use Feralygon\Kit\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Text utility bulletify method options class.
 * 
 * @since 1.0.0
 * @property string $bullet [default = "\u{2022}"] <p>The bullet character to use.</p>
 * @see \Feralygon\Kit\Utilities\Text
 */
class Bulletify extends Options
{
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function buildProperty(string $name) : ?Property
	{
		switch ($name) {
			case 'bullet':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateString($value) && UText::length($value, true) === 1;
					})
					->setDefaultValue("\u{2022}")
				;
		}
		return null;
	}
}