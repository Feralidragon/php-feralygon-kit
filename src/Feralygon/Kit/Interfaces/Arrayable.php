<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Interfaces;

/**
 * This interface defines a method to cast an object to an array.
 * 
 * @since 1.0.0
 */
interface Arrayable
{
	//Public methods
	/**
	 * Cast this object to an array.
	 * 
	 * @since 1.0.0
	 * @param bool $recursive [default = false]
	 * <p>Cast all possible referenced subobjects to arrays recursively (if applicable).</p>
	 * @return array
	 * <p>This object cast to an array.</p>
	 */
	public function toArray(bool $recursive = false): array;
}
