<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Input\Prototypes;

use Feralygon\Kit\Prototype;

/**
 * Input modifier prototype class.
 * 
 * @since 1.0.0
 * @see \Feralygon\Kit\Components\Input\Components\Modifier
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\Name
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\Error
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\Priority
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\Information
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\Stringification
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\ErrorInformation
 * @see \Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\SchemaData
 */
abstract class Modifier extends Prototype {}