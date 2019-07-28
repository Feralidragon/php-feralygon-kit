<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Root\System\Prototypes\Environments;

use Feralygon\Kit\Root\System\Prototypes\Environment;
use Feralygon\Kit\Root\System\Enumerations\DumpVerbosityLevel as EDumpVerbosityLevel;
use Feralygon\Kit\Root\System;

/** This environment prototype sets the system for production. */
class Production extends Environment
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'production';
	}
	
	/** {@inheritdoc} */
	public function isDebug(): bool
	{
		return false;
	}
	
	/** {@inheritdoc} */
	public function getDumpVerbosityLevel(): int
	{
		return EDumpVerbosityLevel::LOW;
	}
	
	/** {@inheritdoc} */
	public function apply(): void
	{
		System::setIniOption('display_errors', false);
		System::setErrorReportingFlags(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);
	}
}
