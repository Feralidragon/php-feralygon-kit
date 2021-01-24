<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Components\Type\Enumerations;

use Dracodeum\Kit\Enumeration;

/** This enumeration is used to define the behavior and output of a type component. */
class Context extends Enumeration
{
	//Public constants
	/** The context of the internal application. */
	public const INTERNAL = 'INTERNAL';
	
	/** The context of a configuration file or environment variable. */
	public const CONFIGURATION = 'CONFIGURATION';
	
	/** The context of an API, CLI or similar interface. */
	public const INTERFACE = 'INTERFACE';
}