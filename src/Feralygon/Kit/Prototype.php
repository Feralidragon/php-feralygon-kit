<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit;

use Feralygon\Kit\Prototype\{
	Exceptions,
	Interfaces
};

/**
 * Prototype class.
 * 
 * This class is the base to be extended from when creating a prototype.<br>
 * For more information, please check the <code>Feralygon\Kit\Component</code> class.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Component
 * @see \Feralygon\Kit\Prototype\Interfaces\Properties
 * @see \Feralygon\Kit\Prototype\Interfaces\Functions
 * @see \Feralygon\Kit\Prototype\Interfaces\Initialization
 */
abstract class Prototype
{
	//Traits
	use Traits\LazyProperties;
	use Traits\Functions;
	
	
	
	//Final public magic methods
	/**
	 * Instantiate class.
	 *
	 * @since 1.0.0
	 * @param array $properties [default = []] <p>The properties, as <samp>name => value</samp> pairs.</p>
	 * @throws \Feralygon\Kit\Prototype\Exceptions\PropertiesNotImplemented
	 */
	final public function __construct(array $properties = [])
	{
		//properties
		if ($this instanceof Interfaces\Properties) {
			$this->initializeProperties([$this, 'buildProperty'], $properties, $this->getRequiredPropertyNames());
		} elseif (!empty($properties)) {
			throw new Exceptions\PropertiesNotImplemented(['prototype' => $this]);
		}
		
		//functions
		if ($this instanceof Interfaces\Functions) {
			$this->initializeFunctions([$this, 'getFunctionTemplate'], true);
		}
		
		//initialization
		if ($this instanceof Interfaces\Initialization) {
			$this->initialize();
		}
	}
}