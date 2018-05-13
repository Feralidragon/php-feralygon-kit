<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Managers\Memoization\Exceptions;

use Feralygon\Kit\Managers\Memoization\Exception;

/**
 * This exception is thrown from a memoization manager whenever a value has not been found at a given key.
 * 
 * @since 1.0.0
 * @property-read string $key
 * <p>The key.</p>
 * @property-read string $namespace [default = '']
 * <p>The namespace.</p>
 */
class ValueNotFound extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return $this->get('namespace') !== ''
			? "No value has been found at key {{key}} in namespace {{namespace}} " . 
				"in memoization manager with owner {{manager.getOwner()}}."
			: "No value has been found at key {{key}} in memoization manager with owner {{manager.getOwner()}}.";
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function loadProperties() : void
	{
		//parent
		parent::loadProperties();
		
		//properties
		$this->addProperty('key')->setAsString()->setAsRequired();
		$this->addProperty('namespace')->setAsString()->setDefaultValue('');
	}
}
