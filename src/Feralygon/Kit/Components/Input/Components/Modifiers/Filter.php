<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Components\Input\Components\Modifiers;

use Feralygon\Kit\Components\Input\Components\Modifier;
use Feralygon\Kit\Prototypes\Input\Prototypes\Modifiers\Filter as Prototype;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Utilities\Text as UText;

/**
 * Input filter modifier component class.
 * 
 * This component represents a filter modifier which processes an input value.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifiers\Filter
 */
class Filter extends Modifier
{
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function handleValueEvaluation(&$value) : bool
	{
		return $this->getPrototype()->processValue($value);
	}
	
	/** {@inheritdoc} */
	protected function getDefaultErrorMessage(TextOptions $text_options) : string
	{
		return UText::localize("The given value failed to be processed.", self::class, $text_options);
	}
	
	
	
	//Implemented protected static methods
	/** {@inheritdoc} */
	protected static function getBasePriority() : int
	{
		return 500;
	}
	
	
	
	//Overridden public static methods
	/** {@inheritdoc} */
	public static function getPrototypeBaseClass() : string
	{
		return Prototype::class;
	}
}