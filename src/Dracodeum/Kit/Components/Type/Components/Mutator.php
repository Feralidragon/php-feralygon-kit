<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Components\Type\Components;

use Dracodeum\Kit\Component;
use Dracodeum\Kit\Components\Type\Prototypes\Mutator as Prototype;
use Dracodeum\Kit\Primitives\{
	Error,
	Text
};
use Dracodeum\Kit\Components\Type\Prototypes\Mutator\Interfaces as PrototypeInterfaces;
use Dracodeum\Kit\Utilities\Call as UCall;

/**
 * This component represents a mutator which processes and modifies values from a type component.
 * 
 * @see \Dracodeum\Kit\Components\Type\Prototypes\Mutator
 */
class Mutator extends Component
{
	//Implemented public static methods
	/** {@inheritdoc} */
	public static function getPrototypeBaseClass(): string
	{
		return Prototype::class;
	}
	
	
	
	//Final public methods
	/**
	 * Process a given value.
	 * 
	 * @param mixed $value [reference]
	 * <p>The value to process.</p>
	 * @return \Dracodeum\Kit\Primitives\Error|null
	 * <p>An error instance if the given value failed to be processed or <code>null</code> if otherwise.</p>
	 */
	final public function process(mixed &$value): ?Error
	{
		//initialize
		$v = $value;
		
		//process
		$error = $this->getPrototype()->process($v);
		if ($error !== null) {
			$error_text = $error->getText() ?? $this->getExplanation() ?? Text::build();
			if (!$error_text->hasString()) {
				$error_text->setString("The given value failed to be processed.")->setAsLocalized(self::class);
			}
			$error->setText($error_text);
			return $error;
		}
		
		//finalize
		$value = $v;
		
		//return
		return null;
	}
	
	/**
	 * Get explanation.
	 * 
	 * @return \Dracodeum\Kit\Primitives\Text|null
	 * <p>The explanation, as a text instance, or <code>null</code> if none is set.</p>
	 */
	final public function getExplanation(): ?Text
	{
		//initialize
		$prototype = $this->getPrototype();
		
		//return
		return $prototype instanceof PrototypeInterfaces\ExplanationProducer
			? UCall::guardExecution([$prototype, 'produceExplanation'], [], [Text::class, 'coerce'])
			: null;
	}
}
