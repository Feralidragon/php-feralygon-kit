<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Options;

use Dracodeum\Kit\Exception as KitException;
use Dracodeum\Kit\Options;

/**
 * @property-read \Dracodeum\Kit\Options|string $options
 * <p>The options instance or class.</p>
 */
abstract class Exception extends KitException
{
	//Implemented protected methods (Dracodeum\Kit\Exception\Traits\PropertiesLoader)
	/** {@inheritdoc} */
	protected function loadProperties(): void
	{
		$this->addProperty('options')->setAsObjectClass(Options::class);
	}
}
