<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Traits;

use Feralygon\Kit\Utilities\Call as UCall;

/**
 * This trait turns a class into an uninstantiable one by preventing its instantiation.
 * 
 * @see \Feralygon\Kit\Interfaces\Uninstantiable
 */
trait Uninstantiable
{
	//Final public magic methods
	/** Prevent class from being instantiated. */
	final public function __construct()
	{
		UCall::guard(false, ['error_message' => "This class cannot be instantiated."]);
	}
}