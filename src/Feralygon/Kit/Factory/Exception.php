<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Factory;

use Feralygon\Kit\Exception as KitException;
use Feralygon\Kit\Factory;

/**
 * Factory exception class.
 * 
 * @since 1.0.0
 * @property-read string $factory <p>The factory class.</p>
 * @see \Feralygon\Kit\Factory
 */
abstract class Exception extends KitException
{
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		$this->addProperty('factory')->setAsStrictClass(Factory::class)->setAsRequired();
	}
}