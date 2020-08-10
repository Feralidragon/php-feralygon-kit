<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Options\Traits;

/** This trait defines a method to extract properties from a callable in an options class. */
trait CallablePropertiesExtractor
{
	//Protected static methods
	/**
	 * Extract properties from a given callable.
	 * 
	 * @param callable $callable
	 * <p>The callable to extract from.</p>
	 * @return array|null
	 * <p>The extracted properties from the given callable, as <samp>name => value</samp> pairs, 
	 * or <code>null</code> if none could be extracted.</p>
	 */
	protected static function extractCallableProperties(callable $callable): ?array
	{
		return null;
	}
}
