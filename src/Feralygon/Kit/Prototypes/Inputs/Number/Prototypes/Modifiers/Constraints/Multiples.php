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
 * Number input multiples constraint modifier prototype class.
 * 
 * This constraint prototype restricts a number to a set of allowed multiples.
 * 
 * @since 1.0.0
 * @property int[]|float[] $multiples <p>The allowed multiples to restrict to.<br>
 * They must all be different from <code>0</code>.</p>
 * @property bool $negate [default = false] <p>Negate the restriction, 
 * so the given allowed multiples act as disallowed multiples instead.</p>
 * @see \Feralygon\Kit\Prototypes\Inputs\Number
 */
class Multiples extends Constraint implements IPrototypeProperties, IName, IInformation, IStringification, ISchemaData
{
	//Private properties
	/** @var int[]|float[] */
	private $multiples;
	
	/** @var bool */
	private $negate = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value) : bool
	{
		foreach ($this->multiples as $multiple) {
			if (is_int($multiple) && is_int($value) && $value % $multiple === 0) {
				return !$this->negate;
			} elseif (is_float($multiple) || is_float($value)) {
				$f = (float)$value / (float)$multiple;
				if ($f === floor($f)) {
					return !$this->negate;
				}
			}
		}
		return $this->negate;
	}
	
	
	
	//Implemented public methods (prototype properties interface)
	/** {@inheritdoc} */
	public function buildProperty(string $name) : ?Property
	{
		switch ($name) {
			case 'multiples':
				return $this->createProperty()
					->setAsArray(function (&$key, &$value) : bool {
						return UType::evaluateNumber($value) && !empty($value);
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
		return ['multiples'];
	}
	
	
	
	//Implemented public methods (input modifier prototype name interface)
	/** {@inheritdoc} */
	public function getName() : string
	{
		return 'constraints.multiples';
	}
	
	
	
	//Implemented public methods (input modifier prototype information interface)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		return $this->negate
			? UText::plocalize(
				"Disallowed multiple", "Disallowed multiples",
				count($this->multiples), null, self::class, $text_options
			)
			: UText::plocalize(
				"Allowed multiple", "Allowed multiples",
				count($this->multiples), null, self::class, $text_options
			);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		$multiples_string = UText::stringify($this->multiples, $text_options, [
			'non_assoc_mode' => UText::STRING_NONASSOC_MODE_COMMA_LIST_OR
		]);
		if ($this->negate) {
			/**
			 * @placeholder multiples The list of disallowed multiples.
			 * @example A multiple of 2, 3 or 5 is not allowed.
			 */
			return UText::localize(
				"A multiple of {{multiples}} is not allowed.",
				self::class, $text_options, [
					'parameters' => ['multiples' => $multiples_string]
				]
			);
		}
		/**
		 * @placeholder multiples The list of allowed multiples.
		 * @example Only a multiple of 2, 3 or 5 is allowed.
		 */
		return UText::localize(
			"Only a multiple of {{multiples}} is allowed.",
			self::class, $text_options, [
				'parameters' => ['multiples' => $multiples_string]
			]
		);
	}
	
	
	
	//Implemented public methods (input modifier prototype stringification interface)
	/** {@inheritdoc} */
	public function getString(TextOptions $text_options) : string
	{
		return UText::stringify($this->multiples, $text_options, [
			'non_assoc_mode' => UText::STRING_NONASSOC_MODE_COMMA_LIST_AND
		]);
	}
	
	
	
	//Implemented public methods (input modifier prototype schema data interface)
	/** {@inheritdoc} */
	public function getSchemaData()
	{
		return [
			'multiples' => $this->multiples,
			'negate' => $this->negate
		];
	}
}