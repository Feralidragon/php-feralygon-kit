<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Component\Traits;

/** This trait defines a method to get the default builder function from a component. */
trait DefaultBuilder
{
	//Protected static methods
	/**
	 * Get default builder function.
	 * 
	 * The returning function is used to build an instance during a coercion or evaluation if none is given, 
	 * and it is expected to be compatible with the following signature:<br>
	 * <br>
	 * <code>function ($prototype, array $properties): Dracodeum\Kit\Component</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>Dracodeum\Kit\Prototype|string|null $prototype</b></code><br>
	 * &nbsp; &nbsp; &nbsp; The prototype instance, class or name to build with.<br>
	 * &nbsp; &nbsp; &nbsp; If not set, then the default prototype instance or the base prototype class is used.<br>
	 * &nbsp; &#8226; &nbsp; <code><b>array $properties</b></code><br>
	 * &nbsp; &nbsp; &nbsp; The properties to build with, as <samp>name => value</samp> pairs.<br>
	 * &nbsp; &nbsp; &nbsp; Required properties may also be given as an array of values 
	 * (<samp>[value1, value2, ...]</samp>), in the same order as how these properties were first declared.<br>
	 * <br>
	 * Return: <code><b>Dracodeum\Kit\Component</b></code><br>
	 * The built instance with the given prototype and set of properties.
	 * 
	 * @return callable|null
	 * <p>The default builder function or <code>null</code> if none is set.</p>
	 */
	protected static function getDefaultBuilder(): ?callable
	{
		return null;
	}
}
