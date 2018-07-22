<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Input\Interfaces;

use Feralygon\Kit\Components\Input\Components\Modifier;

/**
 * This interface defines a method to build modifier instances in an input prototype.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Prototypes\Input
 */
interface ModifierBuilder
{
	//Public methods
	/**
	 * Build modifier instance for a given name.
	 * 
	 * @since 1.0.0
	 * @param string $name
	 * <p>The name to build for.</p>
	 * @param array $properties [default = []]
	 * <p>The properties to build with, as <samp>name => value</samp> pairs.</p>
	 * @return \Feralygon\Kit\Components\Input\Components\Modifier|null
	 * <p>The built modifier instance for the given name or <code>null</code> if none was built.</p>
	 */
	public function buildModifier(string $name, array $properties = []): ?Modifier;
}