<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Prototypes;

use Dracodeum\Kit\Prototype;
use Dracodeum\Kit\Primitives\Error;

/**
 * @see \Dracodeum\Kit\Components\Type
 * @see \Dracodeum\Kit\Prototypes\Type\Interfaces\InformationProducer
 * @see \Dracodeum\Kit\Prototypes\Type\Interfaces\MutatorProducer
 * @see \Dracodeum\Kit\Prototypes\Type\Interfaces\Textifier
 */
abstract class Type extends Prototype
{
	//Abstract public methods
	/**
	 * Process a given value.
	 * 
	 * @param mixed $value [reference]
	 * <p>The value to process.</p>
	 * @param enum:value(Dracodeum\Kit\Components\Type\Enumerations\Context) $context
	 * <p>The context to process for.</p>
	 * @return \Dracodeum\Kit\Primitives\Error|null
	 * <p>An error instance if the given value failed to be processed or <code>null</code> if otherwise.</p>
	 */
	abstract public function process(mixed &$value, $context): ?Error;
}