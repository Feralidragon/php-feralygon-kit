<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Dictionary\Constraints;

use Feralygon\Kit\Components\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Subtype as ISubtype,
	Information as IInformation
};
use Feralygon\Kit\Primitives\Dictionary as Primitive;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Utilities\{
	Data as UData,
	Text as UText
};

/** This constraint prototype restricts a dictionary to unique values. */
class Unique extends Constraint implements IName, ISubtype, IInformation
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value): bool
	{
		if (is_object($value) && $value instanceof Primitive) {
			$map = [];
			foreach ($value as $v) {
				$key = UData::keyfy($v);
				if (isset($map[$key])) {
					return false;
				}
				$map[$key] = true;
			}
			return true;
		}
		return false;
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Name)
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'unique';
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Subtype)
	/** {@inheritdoc} */
	public function getSubtype(): string
	{
		return 'dictionary';
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Information)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options): string
	{
		return UText::localize("Unique", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options): string
	{
		return UText::localize("Only unique values are allowed.", self::class, $text_options);
	}
}
