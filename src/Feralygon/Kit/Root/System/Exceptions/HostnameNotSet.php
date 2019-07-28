<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Root\System\Exceptions;

use Feralygon\Kit\Root\System\Exception;

/** This exception is thrown from the system whenever a hostname is not set. */
class HostnameNotSet extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage(): string
	{
		return "No hostname set.";
	}
}
