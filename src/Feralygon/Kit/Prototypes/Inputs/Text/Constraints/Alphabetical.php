<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Text\Constraints;

use Feralygon\Kit\Components\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Information as IInformation,
	SchemaData as ISchemaData
};
use Feralygon\Kit\Traits\LazyProperties\Property;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Enumerations\InfoScope as EInfoScope;
use Feralygon\Kit\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * This constraint prototype restricts a text or string to alphabetical characters.
 * 
 * @property-write bool $unicode [writeonce] [coercive] [default = false]
 * <p>Check a given text or string as Unicode.</p>
 * @see \Feralygon\Kit\Prototypes\Inputs\Text
 */
class Alphabetical extends Constraint implements IName, IInformation, ISchemaData
{
	//Protected properties
	/** @var bool */
	protected $unicode = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value): bool
	{
		return UType::evaluateString($value) && preg_match($this->unicode ? '/^\pL*$/u' : '/^[a-z]*$/i', $value);
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Name)
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'constraints.alphabetical';
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Information)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options): string
	{
		return UText::localize("Alphabetic characters only", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options): string
	{
		//unicode
		if ($this->unicode) {
			return UText::localize("Only alphabetic characters are allowed.", self::class, $text_options);
		}
		
		//end-user
		if ($text_options->info_scope === EInfoScope::ENDUSER) {
			/**
			 * @placeholder letters.a The lowercase "a" letter character.
			 * @placeholder letters.z The lowercase "z" letter character.
			 * @placeholder letters.A The uppercase "A" letter character.
			 * @placeholder letters.Z The uppercase "Z" letter character.
			 * @tags end-user
			 * @example Only alphabetic characters (a-z and A-Z) are allowed.
			 */
			return UText::localize(
				"Only alphabetic characters ({{letters.a}}-{{letters.z}} and {{letters.A}}-{{letters.Z}}) are allowed.",
				self::class, $text_options, [
					'parameters' => [
						'letters' => ['a' => 'a', 'z' => 'z', 'A' => 'A', 'Z' => 'Z']
					]
				]
			);
		}
		
		//non-end-user
		/**
		 * @placeholder letters.a The lowercase "a" letter character.
		 * @placeholder letters.z The lowercase "z" letter character.
		 * @placeholder letters.A The uppercase "A" letter character.
		 * @placeholder letters.Z The uppercase "Z" letter character.
		 * @tags non-end-user
		 * @example Only ASCII alphabetic characters (a-z and A-Z) are allowed.
		 */
		return UText::localize(
			"Only ASCII alphabetic characters ({{letters.a}}-{{letters.z}} and " . 
				"{{letters.A}}-{{letters.Z}}) are allowed.",
			self::class, $text_options, [
				'parameters' => [
					'letters' => ['a' => 'a', 'z' => 'z', 'A' => 'A', 'Z' => 'Z']
				]
			]
		);
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
				return $this->createProperty()->setMode('w-')->setAsBoolean()->bind(self::class);
		}
		return null;
	}
}