<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Traits\Functions;

use Feralygon\Kit\Exception as KitException;

/**
 * Functions trait exception class.
 * 
 * @since 1.0.0
 * @property-read object $object <p>The object.</p>
 * @see \Feralygon\Kit\Traits\Functions
 */
abstract class Exception extends KitException
{
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function buildProperties() : void
	{
		$this->addProperty('object')->setAsStrictObject()->setAsRequired();
	}
}