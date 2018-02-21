<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Number\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Prototypes\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Prototype\Interfaces\Properties as IPrototypeProperties;
use Feralygon\Kit\Prototypes\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Information as IInformation,
	Stringification as IStringification,
	SchemaData as ISchemaData
};
use Feralygon\Kit\Traits\LazyProperties\Objects\Property;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Number input powers constraint modifier prototype class.
 * 
 * This constraint prototype restricts a number to a set of allowed powers.
 * 
 * @since 1.0.0
 * @property int[]|float[] $powers <p>The allowed powers to restrict to.<br>
 * They must all be greater than <code>0</code>.</p>
 * @property bool $negate [default = false] <p>Negate the restriction, 
 * so the given allowed powers act as disallowed powers instead.</p>
 * @see \Feralygon\Kit\Prototypes\Inputs\Number
 */
class Powers extends Constraint implements IPrototypeProperties, IName, IInformation, IStringification, ISchemaData
{
	//Private properties
	/** @var int[]|float[] */
	private $powers;
	
	/** @var bool */
	private $negate = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value) : bool
	{
		foreach ($this->powers as $power) {
			$f = log($value, $power);
			if ($f === floor($f)) {
				return !$this->negate;
			}
		}
		return $this->negate;
	}
	
	
	
	//Implemented public methods (prototype properties interface)
	/** {@inheritdoc} */
	public function buildProperty(string $name) : ?Property
	{
		switch ($name) {
			case 'powers':
				return $this->createProperty()
					->setAsArray(function (&$key, &$value) : bool {
						return UType::evaluateNumber($value) && $value > 0;
					}, true, true)
					->bind(self::class)
				;
			case 'negate':
				return $this->createProperty()->setAsBoolean()->bind(self::class);
		}
		return null;
	}
	
	
	
	//Implemented public static methods (prototype properties interface)
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return ['powers'];
	}
	
	
	
	//Implemented public methods (input modifier prototype name interface)
	/** {@inheritdoc} */
	public function getName() : string
	{
		return 'constraints.powers';
	}
	
	
	
	//Implemented public methods (input modifier prototype information interface)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		return $this->negate
			? UText::plocalize(
				"Disallowed power", "Disallowed powers",
				count($this->powers), null, self::class, $text_options
			)
			: UText::plocalize(
				"Allowed power", "Allowed powers",
				count($this->powers), null, self::class, $text_options
			);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		$powers_string = UText::stringify($this->powers, $text_options, [
			'non_assoc_mode' => UText::STRING_NONASSOC_MODE_COMMA_LIST_OR
		]);
		if ($this->negate) {
			/**
			 * @placeholder powers The list of disallowed powers.
			 * @example A power of 2, 3 or 5 is not allowed.
			 */
			return UText::localize(
				"A power of {{powers}} is not allowed.",
				self::class, $text_options, [
					'parameters' => ['powers' => $powers_string]
				]
			);
		}
		/**
		 * @placeholder powers The list of allowed powers.
		 * @example Only a power of 2, 3 or 5 is allowed.
		 */
		return UText::localize(
			"Only a power of {{powers}} is allowed.",
			self::class, $text_options, [
				'parameters' => ['powers' => $powers_string]
			]
		);
	}
	
	
	
	//Implemented public methods (input modifier prototype stringification interface)
	/** {@inheritdoc} */
	public function getString(TextOptions $text_options) : string
	{
		return UText::stringify($this->powers, $text_options, [
			'non_assoc_mode' => UText::STRING_NONASSOC_MODE_COMMA_LIST_AND
		]);
	}
	
	
	
	//Implemented public methods (input modifier prototype schema data interface)
	/** {@inheritdoc} */
	public function getSchemaData()
	{
		return [
			'powers' => $this->powers,
			'negate' => $this->negate
		];
	}
}