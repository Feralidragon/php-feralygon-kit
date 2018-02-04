<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Components\Input;

use Feralygon\Kit\Core;
use Feralygon\Kit\Core\Components\Input;

/**
 * Core input component exception class.
 * 
 * @since 1.0.0
 * @property-read \Feralygon\Kit\Core\Components\Input $component <p>The input component instance.</p>
 * @property-read \Feralygon\Kit\Core\Prototypes\Input $prototype <p>The input prototype instance.</p>
 * @see \Feralygon\Kit\Core\Components\Input
 */
abstract class Exception extends Core\Exception
{
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function loadProperties() : void
	{
		$this->addStrictObjectProperty('component', true, Input::class);
		$this->addStrictObjectProperty('prototype', true, Input::getPrototypeBaseClass());
	}
}
