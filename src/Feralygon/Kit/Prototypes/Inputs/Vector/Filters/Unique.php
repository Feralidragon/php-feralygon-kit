<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Vector\Filters;

use Feralygon\Kit\Components\Input\Prototypes\Modifiers\Filter;
use Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\{
	Subtype as ISubtype,
	Information as IInformation
};
use Feralygon\Kit\Primitives\Vector as Primitive;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Enumerations\InfoScope as EInfoScope;
use Feralygon\Kit\Utilities\Text as UText;

/** This filter prototype removes duplicated values from a vector. */
class Unique extends Filter implements ISubtype, IInformation
{
	//Implemented public methods
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'unique';
	}
	
	/** {@inheritdoc} */
	public function processValue(&$value): bool
	{
		if (is_object($value) && $value instanceof Primitive) {
			$value->unique();
			return true;
		}
		return false;
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Subtype)
	/** {@inheritdoc} */
	public function getSubtype(): string
	{
		return 'vector';
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
		//end-user
		if ($text_options->info_scope === EInfoScope::ENDUSER) {
			/** @tags end-user */
			return UText::localize("Duplicated items are removed.", self::class, $text_options);
		}
		
		//non-end-user
		/** @tags non-end-user */
		return UText::localize("Duplicated values are removed.", self::class, $text_options);
	}
}
