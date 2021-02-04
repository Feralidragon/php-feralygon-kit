<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Components\Type;

use Dracodeum\Kit\Exception as KitException;
use Dracodeum\Kit\Components\Type as Component;

/**
 * @property-read \Dracodeum\Kit\Components\Type $component
 * The component instance.
 * 
 * @property-read \Dracodeum\Kit\Prototypes\Type $prototype
 * The prototype instance.
 */
abstract class Exception extends KitException
{
	//Implemented protected methods (Dracodeum\Kit\Exception\Traits\PropertiesLoader)
	/** {@inheritdoc} */
	protected function loadProperties(): void
	{
		$this->addProperty('component')->setAsStrictObject(Component::class);
		$this->addProperty('prototype')->setAsStrictObject(Component::getPrototypeBaseClass());
	}
}
