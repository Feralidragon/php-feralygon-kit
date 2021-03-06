<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Interfaces;

/** This interface defines a method to cast an object to an array. */
interface Arrayable
{
	//Public methods
	/**
	 * Cast this object to an array.
	 * 
	 * @param bool $recursive [default = false]
	 * <p>Cast all the possible referenced subobjects to arrays recursively (if applicable).</p>
	 * @return array
	 * <p>This object cast to an array.</p>
	 */
	public function toArray(bool $recursive = false): array;
}
