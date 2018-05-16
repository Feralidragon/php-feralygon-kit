<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Components\Input\Components\Modifier\Exceptions;

use Feralygon\Kit\Components\Input\Components\Modifier\Exception;

/**
 * This exception is thrown from a modifier whenever no schema is set.
 * 
 * @since 1.0.0
 */
class SchemaNotSet extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "No schema is set in modifier {{component}} (with prototype {{prototype}}).";
	}
}