<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Utilities\Text\Exceptions\Fill;

use Feralygon\Kit\Utilities\Text\Exceptions\Fill as Exception;

/**
 * This exception is thrown from the text utility <code>fill</code> method whenever 
 * a given placeholder key identifier is not found.
 * 
 * @since 1.0.0
 * @property-read string $placeholder
 * <p>The placeholder.</p>
 * @property-read string $identifier
 * <p>The identifier.</p>
 */
class PlaceholderKeyIdentifierNotFound extends Exception
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getDefaultMessage() : string
	{
		return "Placeholder {{placeholder}} key identifier {{identifier}} not found.";
	}
	
	
	
	//Implemented protected methods
	/** {@inheritdoc} */
	protected function loadProperties() : void
	{
		$this->addProperty('placeholder')->setAsString()->setAsRequired();
		$this->addProperty('identifier')->setAsString()->setAsRequired();
	}
}