<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototype\Interfaces;

use Feralygon\Kit\Traits\LazyProperties\Objects\Property;

/**
 * Prototype properties interface.
 * 
 * This interface defines a set of methods to build and retrieve properties from a prototype.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Prototype
 */
interface Properties
{
	//Public methods
	/**
	 * Build property instance for a given name.
	 * 
	 * @since 1.0.0
	 * @param string $name <p>The property name to build for.</p>
	 * @return \Feralygon\Kit\Traits\LazyProperties\Objects\Property|null 
	 * <p>The built property instance for the given name or <code>null</code> if none was built.</p>
	 */
	public function buildProperty(string $name) : ?Property;
	
	
	
	//Public static methods
	/**
	 * Get required property names.
	 * 
	 * All the required properties returned here must be given during instantiation.
	 * 
	 * @since 1.0.0
	 * @return string[] <p>The required property names.</p>
	 */
	public static function getRequiredPropertyNames() : array;
}