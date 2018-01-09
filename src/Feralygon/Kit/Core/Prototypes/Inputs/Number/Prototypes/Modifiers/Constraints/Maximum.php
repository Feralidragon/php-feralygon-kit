<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Prototypes\Inputs\Number\Prototypes\Modifiers\Constraints;

use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifiers\Constraint;
use Feralygon\Kit\Core\Prototype\Interfaces\Properties as IPrototypeProperties;
use Feralygon\Kit\Core\Prototypes\Input\Prototypes\Modifier\Interfaces\{
	Name as IName,
	Information as IInformation,
	Stringification as IStringification,
	SpecificationData as ISpecificationData
};
use Feralygon\Kit\Core\Traits\ExtendedProperties\Objects\Property;
use Feralygon\Kit\Core\Options\Text as TextOptions;
use Feralygon\Kit\Core\Utilities\{
	Text as UText,
	Type as UType
};

/**
 * Core number input maximum constraint modifier prototype class.
 * 
 * This input constraint modifier prototype restricts a number to a maximum value.
 * 
 * @since 1.0.0
 * @property int|float $value <p>The maximum allowed value to restrict to (inclusive).</p>
 * @property bool $exclusive [default = false] <p>Set the maximum allowed value as exclusive, restricting a given value to always be lesser than the maximum allowed value, but never equal.</p>
 * @see \Feralygon\Kit\Core\Prototypes\Inputs\Number
 */
class Maximum extends Constraint implements IPrototypeProperties, IName, IInformation, IStringification, ISpecificationData
{
	//Private properties
	/** @var int|float */
	private $value;
	
	/** @var bool */
	private $exclusive = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function checkValue($value) : bool
	{
		return $this->exclusive ? $value < $this->value : $value <= $this->value;
	}
	
	
	
	//Implemented public methods (core prototype properties interface)
	/** {@inheritdoc} */
	public function buildProperty(string $name) : ?Property
	{
		switch ($name) {
			case 'value':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateNumber($value);
					})
					->setGetter(function () {
						return $this->value;
					})
					->setSetter(function ($value) : void {
						$this->value = $value;
					})
				;
			case 'exclusive':
				return $this->createProperty()
					->setEvaluator(function (&$value) : bool {
						return UType::evaluateBoolean($value);
					})
					->setGetter(function () : bool {
						return $this->exclusive;
					})
					->setSetter(function (bool $exclusive) : void {
						$this->exclusive = $exclusive;
					})
				;
		}
		return null;
	}
	
	
	
	//Implemented public static methods (core prototype properties interface)
	/** {@inheritdoc} */
	public static function getRequiredPropertyNames() : array
	{
		return ['value'];
	}
	
	
	
	//Implemented public methods (core input modifier prototype name interface)
	/** {@inheritdoc} */
	public function getName() : string
	{
		return 'constraints.maximum';
	}
	
	
	
	//Implemented public methods (core input modifier prototype information interface)
	/** {@inheritdoc} */
	public function getLabel(TextOptions $text_options) : string
	{
		/**
		 * @description Core number input maximum constraint modifier prototype label.
		 * @tags core prototype input number modifier constraint maximum label
		 */
		return UText::localize("Maximum allowed number", 'core.prototypes.inputs.number.prototypes.modifiers.constraints.maximum', $text_options);
	}
	
	/** {@inheritdoc} */
	public function getMessage(TextOptions $text_options) : string
	{
		if ($this->exclusive) {
			/**
			 * @description Core number input maximum constraint modifier prototype message (exclusive).
			 * @placeholder value The maximum allowed value.
			 * @tags core prototype input number modifier constraint maximum message
			 * @example Only numbers lesser than 250 are allowed.
			 */
			return UText::localize(
				"Only numbers lesser than {{value}} are allowed.", 
				'core.prototypes.inputs.number.prototypes.modifiers.constraints.maximum', $text_options, [
					'parameters' => ['value' => $this->value]
				]
			);
		}
		/**
		 * @description Core number input maximum constraint modifier prototype message.
		 * @placeholder value The maximum allowed value.
		 * @tags core prototype input number modifier constraint maximum message
		 * @example Only numbers lesser than or equal to 250 are allowed.
		 */
		return UText::localize(
			"Only numbers lesser than or equal to {{value}} are allowed.", 
			'core.prototypes.inputs.number.prototypes.modifiers.constraints.maximum', $text_options, [
				'parameters' => ['value' => $this->value]
			]
		);
	}
	
	
	
	//Implemented public methods (core input modifier prototype stringification interface)
	/** {@inheritdoc} */
	public function getString(TextOptions $text_options) : string
	{
		if ($this->exclusive) {
			/**
			 * @description Core number input maximum constraint modifier prototype string (exclusive).
			 * @placeholder value The maximum allowed value.
			 * @tags core prototype input number modifier constraint maximum string
			 * @example 250 (exclusive)
			 */
			return UText::localize(
				"{{value}} (exclusive)", 
				'core.prototypes.inputs.number.prototypes.modifiers.constraints.maximum', $text_options, [
					'parameters' => ['value' => $this->value]
				]
			);
		}
		return UText::stringify($this->value, $text_options);
	}
	
	
	
	//Implemented public methods (core input modifier prototype specification data interface)
	/** {@inheritdoc} */
	public function getSpecificationData()
	{
		return [
			'exclusive' => $this->exclusive,
			'value' => $this->value
		];
	}
}
