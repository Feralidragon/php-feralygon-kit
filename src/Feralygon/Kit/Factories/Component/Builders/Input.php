<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Factories\Component\Builders;

use Feralygon\Kit\Factory\Builder;
use Feralygon\Kit\Components;

/**
 * Component factory input builder class.
 * 
 * This builder is used to build input component instances.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Factories\Component
 * @see \Feralygon\Kit\Components\Input [name = '', arguments = 1-3]
 */
class Input extends Builder
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function build(string $name, ...$arguments) : ?object
	{
		switch ($name) {
			case '':
				$this->validateArguments($arguments, 1, 3);
				return new Components\Input(...$arguments);
		}
		return null;
	}
}
