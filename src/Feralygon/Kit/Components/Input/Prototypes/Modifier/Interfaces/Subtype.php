<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces;

/** This interface defines a method to get the subtype from an input modifier prototype. */
interface Subtype
{
	//Public methods
	/**
	 * Get subtype.
	 * 
	 * The returning subtype must be a canonical string.
	 * 
	 * @return string
	 * <p>The subtype.</p>
	 */
	public function getSubtype(): string;
}
