<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Components\Input\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Components\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Information as IInformation,
	SchemaData as ISchemaData
};
use Feralygon\Kit\Traits\LazyProperties\Property;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * This constraint prototype restricts a value to lowercase.
 * 
 * @property-write bool $unicode [writeonce] [transient] [coercive] [default = false]
 * <p>Check a given value as Unicode.</p>
 */
class Lowercase extends Constraint implements IName, IInformation, ISchemaData
{
	//Protected properties
	/** @var bool */
	protected $unicode = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value): bool
	{
		return UType::evaluateString($value) && $value === UText::lower($value, $this->unicode);
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Name)
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'lowercase';
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Information)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options): string
	{
		return UText::localize("Lowercase only", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options): string
	{
		return UText::localize("Only lowercase is allowed.", self::class, $text_options);
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\SchemaData)
	/** {@inheritdoc} */
	public function getSchemaData()
	{
		return [
			'unicode' => $this->unicode
		];
	}
	
	
	
	//Implemented protected methods (Feralygon\Kit\Prototype\Traits\PropertyBuilder)
	/** {@inheritdoc} */
	protected function buildProperty(string $name): ?Property
	{
		switch ($name) {
			case 'unicode':
				return $this->createProperty()->setMode('w--')->setAsBoolean()->bind(self::class);
		}
		return null;
	}
}
