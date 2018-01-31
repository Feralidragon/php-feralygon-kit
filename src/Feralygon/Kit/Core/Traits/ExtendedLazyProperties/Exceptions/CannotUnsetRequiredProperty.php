<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Traits\ExtendedLazyProperties\Exceptions;

/**
 * Core extended lazy properties trait cannot unset required property exception class.
 * 
 * This exception is thrown from an object using the extended lazy properties trait whenever a given required property 
 * with a given name is attempted to be unset.
 * 
 * @since 1.0.0
 */
class CannotUnsetRequiredProperty extends CannotUnsetProperty
{
	//Overridden public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Cannot unset required property {{name}} from object {{object}}.";
	}
}