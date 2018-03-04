<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Factories\Component\Builders;

use Feralygon\Kit\Factory\Builder;
use Feralygon\Kit\Factories\Component\Builder\Interfaces\Input as IBuilder;
use Feralygon\Kit\Components\Input as Component;

/**
 * Component factory input builder class.
 * 
 * This builder is used to build input component instances.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Factories\Component
 * @see \Feralygon\Kit\Components\Input [object]
 */
class Input extends Builder implements IBuilder
{
	//Implemented public methods (component factory input builder interface)
	/** {@inheritdoc} */
	public function build($prototype, array $properties = [], array $prototype_properties = []) : Component
	{
		return new Component($prototype, $properties, $prototype_properties);
	}
}