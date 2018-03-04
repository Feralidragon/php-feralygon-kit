<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Traits;

use Feralygon\Kit\Interfaces\Stringifiable as IStringifiable;
use Feralygon\Kit\Utilities\Call as UCall;

/**
 * Stringifiable trait.
 * 
 * This trait adds the ability for a class to be converted into a string, 
 * by using the <code>Feralygon\Kit\Interfaces\Stringifiable</code> interface.
 * 
 * @since 1.0.0
 */
trait Stringifiable
{
	//Final public magic methods
	/**
	 * Convert this object into a string.
	 * 
	 * This method requires this object to have 
	 * the <code>Feralygon\Kit\Interfaces\Stringifiable</code> interface implemented.
	 * 
	 * @since 1.0.0
	 * @return string <p>This object converted into a string.</p>
	 */
	final public function __toString() : string
	{
		UCall::guard($this instanceof IStringifiable, [
			'hint_message' => "This method requires this object to have " . 
				"the \"Feralygon\\Kit\\Interfaces\\Stringifiable\" interface implemented."
		]);
		return $this->toString();
	}
}