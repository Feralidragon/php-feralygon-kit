<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Core\Prototype\Interfaces\Properties as IPrototypeProperties;
use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Priority as IPriority,
	Information as IInformation,
	Stringification as IStringification,
	SchemaData as ISchemaData
};
use Feralygon\Kit\Core\Traits\ExtendedProperties\Objects\Property;
use Feralygon\Kit\Core\Options\Text as TextOptions;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Core input length range constraint modifier prototype class.
 * 
 * This constraint prototype restricts a value to a range of lengths.
 * 
 * @since 1.0.0
 * @property int $min_length <p>The minimum length to restrict to.<br>
 * It must be greater than or equal to <code>0</code>.</p>
 * @property int $max_length <p>The maximum length to restrict to.<br>
 * It must be greater than or equal to <code>0</code>.</p>
 * @property bool $unicode [default = false] <p>Check as an Unicode value.</p>
 */
class LengthRange extends Constraint implements IPrototypeProperties, IName, IPriority, IInformation, IStringification, ISchemaData
{
	//Private properties
	/** @var int */
	private $min_length;
	
	/** @var int */
	private $max_length;
	
	/** @var bool */
	private $unicode = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value) : bool
	{
		$length = UText::length($value, $this->unicode);
		return $length >= $this->min_length && $length <= $this->max_length;
	}
	
	
	
	//Implemented public methods (core prototype properties interface)
	/** {@inheritdoc} */
	public function buildProperty(string $name) : ?Property
	{
		switch ($name) {
			case 'min_length':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateInteger($value) && $value >= 0;
					})
					->setGetter(function () : int {
						return $this->min_length;
					})
					->setSetter(function (int $min_length) : void {
						$this->min_length = $min_length;
					})
				;
			case 'max_length':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateInteger($value) && $value >= 0;
					})
					->setGetter(function () : int {
						return $this->max_length;
					})
					->setSetter(function (int $max_length) : void {
						$this->max_length = $max_length;
					})
				;
			case 'unicode':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateBoolean($value);
					})
					->setGetter(function () : bool {
						return $this->unicode;
					})
					->setSetter(function (bool $unicode) : void {
						$this->unicode = $unicode;
					})
				;
		}
		return null;
	}
	
	
	
	//Implemented public static methods (core prototype properties interface)
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return ['min_length', 'max_length'];
	}
	
	
	
	//Implemented public methods (core input modifier prototype name interface)
	/** {@inheritdoc} */
	public function getName() : string
	{
		return 'constraints.length_range';
	}
	
	
	
	//Implemented public methods (core input modifier prototype priority interface)
	/** {@inheritdoc} */
	public function getPriority() : int
	{
		return 250;
	}
	
	
	
	//Implemented public methods (core input modifier prototype information interface)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		return UText::localize("Allowed lengths range", self::class, $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		/**
		 * @placeholder min_length The minimum allowed length.
		 * @placeholder max_length The maximum allowed length.
		 * @example Only between 5 and 10 characters are allowed.
		 */
		return UText::plocalize(
			"Only between {{min_length}} and {{max_length}} character is allowed.",
			"Only between {{min_length}} and {{max_length}} characters are allowed.",
			$this->max_length, 'max_length', self::class, $text_options, [
				'parameters' => ['min_length' => $this->min_length]
			]
		);
	}
	
	
	
	//Implemented public methods (core input modifier prototype stringification interface)
	/** {@inheritdoc} */
	public function getString(TextOptions $text_options) : string
	{
		/**
		 * @placeholder min_length The minimum allowed length.
		 * @placeholder max_length The maximum allowed length.
		 * @example 5 to 10
		 */
		return UText::localize("{{min_length}} to {{max_length}}", self::class, $text_options, ['parameters' => ['min_length' => $this->min_length, 'max_length' => $this->max_length]]);
	}
	
	
	
	//Implemented public methods (core input modifier prototype schema data interface)
	/** {@inheritdoc} */
	public function getSchemaData()
	{
		return [
			'unicode' => $this->unicode,
			'minimum' => [
				'length' => $this->min_length
			],
			'maximum' => [
				'length' => $this->max_length
			]
		];
	}
}