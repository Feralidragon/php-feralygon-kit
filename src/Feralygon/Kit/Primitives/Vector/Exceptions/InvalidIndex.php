<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Primitives\Vector\Exceptions;

use Feralygon\Kit\Primitives\Vector\Exception;

/**
 * This exception is thrown from a vector whenever a given index is invalid.
 * 
 * @since 1.0.0
 * @property-read int $index
 * <p>The index.<br>
 * It must be greater than or equal to <code>0</code>.</p>
 * @property-read int|null $max_index [default = null]
 * <p>The maximum allowed index.<br>
 * If set, then it must be greater than or equal to <code>0</code>.</p>
 */
class InvalidIndex extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage(): string
	{
		$message = "Invalid index {{index}} for vector {{vector}}.";
		if ($this->isset('max_index')) {
			$message .= "\nHINT: Only up to {{max_index}} is allowed.";
		}
		return $message;
	}
	
	
	
	//Overridden protected methods
	/** {@inheritdoc} */
	protected function loadProperties(): void
	{
		//parent
		parent::loadProperties();
		
		//properties
		$this->addProperty('index')->setAsStrictInteger(true);
		$this->addProperty('max_index')->setAsStrictInteger(true, null, true)->setDefaultValue(null);
	}
}
