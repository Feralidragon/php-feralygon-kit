<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Hash\Prototypes\Modifiers\Filters;

use Feralygon\Kit\Prototypes\Input\Prototypes\Modifiers\Filter;

/**
 * This filter prototype converts a hash string in hexadecimal notation into a raw binary string.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Prototypes\Inputs\Hash
 */
class Raw extends Filter
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function processValue(&$value) : bool
	{
		if (is_string($value)) {
			$value = hex2bin($value);
			return $value !== false;
		}
		return false;
	}
}
