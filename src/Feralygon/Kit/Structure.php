<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit;

use Feralygon\Kit\Interfaces\{
	DebugInfo as IDebugInfo,
	Propertiesable as IPropertiesable,
	Arrayable as IArrayable,
	Readonlyable as IReadonlyable,
	ArrayInstantiable as IArrayInstantiable,
	Stringifiable as IStringifiable,
	StringInstantiable as IStringInstantiable
};
use Feralygon\Kit\Structure\{
	Traits,
	Exceptions
};
use Feralygon\Kit\Traits as KitTraits;
use Feralygon\Kit\Traits\DebugInfo\Info as DebugInfo;
use Feralygon\Kit\Traits\DebugInfo\Interfaces\DebugInfoProcessor as IDebugInfoProcessor;
use Feralygon\Kit\Options\Text as TextOptions;
use Feralygon\Kit\Utilities\{
	Call as UCall,
	Data as UData,
	Text as UText,
	Type as UType
};

/**
 * This class is the base to be extended from when creating a structure.
 * 
 * A structure is a simple object which represents and stores multiple properties of multiple types.<br>
 * Each and every single one of its properties is validated and sanitized, guaranteeing its type and integrity, 
 * and may be got and set directly just like any public object property.<br>
 * <br>
 * It may also be set as read-only to prevent any further changes.
 * 
 * @since 1.0.0
 * @see https://en.wikipedia.org/wiki/Struct_(C_programming_language)
 * @see \Feralygon\Kit\Structure\Traits\DefaultBuilder
 * @see \Feralygon\Kit\Structure\Traits\StringPropertiesExtractor
 */
abstract class Structure
implements IDebugInfo, IDebugInfoProcessor, IPropertiesable, \ArrayAccess, IArrayable, \JsonSerializable, IReadonlyable,
IArrayInstantiable, IStringifiable, IStringInstantiable
{
	//Traits
	use KitTraits\DebugInfo;
	use KitTraits\Properties;
	use KitTraits\Properties\Arrayable;
	use KitTraits\Properties\ArrayAccess;
	use KitTraits\Readonly;
	use KitTraits\Stringifiable;
	use Traits\DefaultBuilder;
	use Traits\StringPropertiesExtractor;
	
	
	
	//Final public magic methods
	/**
	 * Instantiate class.
	 * 
	 * @since 1.0.0
	 * @param array $properties [default = []]
	 * <p>The properties to instantiate with, as <samp>name => value</samp> pairs.<br>
	 * Required properties may also be given as an array of values (<samp>[value1, value2, ...]</samp>), 
	 * in the same order as how these properties were first declared.</p>
	 */
	final public function __construct(array $properties = [])
	{
		//properties
		$this->initializeProperties(\Closure::fromCallable([$this, 'loadProperties']), $properties);
		
		//read-only
		$this->addReadonlyCallback(function (bool $recursive): void {
			//properties
			$this->setPropertiesAsReadonly();
			
			//recursive
			if ($recursive) {
				foreach ($this->getAll() as $value) {
					if (is_object($value) && $value instanceof IReadonlyable) {
						$value->setAsReadonly($recursive);
					}
				}
			}
		});
	}
	
	
	
	//Abstract protected methods
	/**
	 * Load properties.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	abstract protected function loadProperties(): void;
	
	
	
	//Implemented public methods (Feralygon\Kit\Traits\DebugInfo\Interfaces\DebugInfoProcessor)
	/** {@inheritdoc} */
	public function processDebugInfo(DebugInfo $info): void
	{
		$this->processReadonlyDebugInfo($info)->processPropertiesDebugInfo($info);
		$info->enableObjectPropertiesDump();
	}
	
	
	
	//Implemented final public methods (JsonSerializable)
	/** {@inheritdoc} */
	final public function jsonSerialize()
	{
		return $this->getAll();
	}
	
	
	
	//Implemented final public static methods (Feralygon\Kit\Interfaces\ArrayInstantiable)
	/** {@inheritdoc} */
	final public static function fromArray(array $array): object
	{
		return static::build($array);
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Interfaces\Stringifiable)
	/** {@inheritdoc} */
	public function toString(?TextOptions $text_options = null): string
	{
		return UText::stringify($this->getAll(), $text_options);
	}
	
	
	
	//Implemented final public static methods (Feralygon\Kit\Interfaces\StringInstantiable)
	/** {@inheritdoc} */
	final public static function fromString(string $string): object
	{
		return static::build(static::getStringProperties($string));
	}
	
	
	
	//Final public methods
	/**
	 * Clone into a new instance.
	 * 
	 * The returning cloned instance is a new instance with the same properties.
	 * 
	 * @since 1.0.0
	 * @return static
	 * <p>The new cloned instance from this one.</p>
	 */
	final public function clone(): Structure
	{
		return new static($this->getAll());
	}
	
	
	
	//Final public static methods
	/**
	 * Build instance.
	 * 
	 * @since 1.0.0
	 * @param array $properties [default = []]
	 * <p>The properties to build with, as <samp>name => value</samp> pairs.</p>
	 * @return static
	 * <p>The built instance.</p>
	 */
	final public static function build(array $properties = []): Structure
	{
		$builder = static::getDefaultBuilder();
		if (isset($builder)) {
			UCall::assert('builder', $builder, function (array $properties): Structure {});
			return $builder($properties);
		}
		return new static($properties);
	}
	
	/**
	 * Get properties from a given string.
	 * 
	 * @since 1.0.0
	 * @param string $string
	 * <p>The string to get from.</p>
	 * @return array
	 * <p>The properties from the given string, as <samp>name => value</samp> pairs.</p>
	 */
	final public static function getStringProperties(string $string): array
	{
		$properties = static::extractStringProperties($string);
		UCall::guardParameter('string', $string, isset($properties), [
			'error_message' => "No properties could be extracted from the given string."
		]);
		return $properties;
	}
	
	/**
	 * Evaluate a given value as an instance.
	 * 
	 * Only the following types and formats can be evaluated into an instance:<br>
	 * &nbsp; &#8226; &nbsp; <code>null</code>, a string or an instance;<br>
	 * &nbsp; &#8226; &nbsp; an array of properties, given as <samp>name => value</samp> pairs;<br>
	 * &nbsp; &#8226; &nbsp; an object implementing the <code>Feralygon\Kit\Interfaces\Arrayable</code> interface.
	 * 
	 * @since 1.0.0
	 * @see \Feralygon\Kit\Interfaces\Arrayable
	 * @param mixed $value [reference]
	 * <p>The value to evaluate (validate and sanitize).</p>
	 * @param bool $clone [default = false]
	 * <p>If an instance is given, then clone it into a new one with the same properties.</p>
	 * @param callable|null $builder [default = null]
	 * <p>The function to use to build an instance.<br>
	 * It is expected to be compatible with the following signature:<br>
	 * <br>
	 * <code>function (array $properties): Feralygon\Kit\Structure</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>array $properties</b></code><br>
	 * &nbsp; &nbsp; &nbsp; The properties to build with, as <samp>name => value</samp> pairs.<br>
	 * &nbsp; &nbsp; &nbsp; Required properties may also be given as an array of values 
	 * (<samp>[value1, value2, ...]</samp>), in the same order as how these properties were first declared.<br>
	 * <br>
	 * Return: <code><b>Feralygon\Kit\Structure</b></code><br>
	 * The built instance.</p>
	 * @param bool $nullable [default = false]
	 * <p>Allow the given value to evaluate as <code>null</code>.</p>
	 * @return bool
	 * <p>Boolean <code>true</code> if the given value was successfully evaluated into an instance.</p>
	 */
	final public static function evaluate(
		&$value, bool $clone = false, ?callable $builder = null, bool $nullable = false
	): bool
	{
		return self::processCoercion($value, $clone, $builder, $nullable, true);
	}
	
	/**
	 * Coerce a given value into an instance.
	 * 
	 * Only the following types and formats can be coerced into an instance:<br>
	 * &nbsp; &#8226; &nbsp; <code>null</code>, a string or an instance;<br>
	 * &nbsp; &#8226; &nbsp; an array of properties, given as <samp>name => value</samp> pairs;<br>
	 * &nbsp; &#8226; &nbsp; an object implementing the <code>Feralygon\Kit\Interfaces\Arrayable</code> interface.
	 * 
	 * @since 1.0.0
	 * @see \Feralygon\Kit\Interfaces\Arrayable
	 * @param mixed $value
	 * <p>The value to coerce (validate and sanitize).</p>
	 * @param bool $clone [default = false]
	 * <p>If an instance is given, then clone it into a new one with the same properties.</p>
	 * @param callable|null $builder [default = null]
	 * <p>The function to use to build an instance.<br>
	 * It is expected to be compatible with the following signature:<br>
	 * <br>
	 * <code>function (array $properties): Feralygon\Kit\Structure</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>array $properties</b></code><br>
	 * &nbsp; &nbsp; &nbsp; The properties to build with, as <samp>name => value</samp> pairs.<br>
	 * &nbsp; &nbsp; &nbsp; Required properties may also be given as an array of values 
	 * (<samp>[value1, value2, ...]</samp>), in the same order as how these properties were first declared.<br>
	 * <br>
	 * Return: <code><b>Feralygon\Kit\Structure</b></code><br>
	 * The built instance.</p>
	 * @param bool $nullable [default = false]
	 * <p>Allow the given value to coerce as <code>null</code>.</p>
	 * @throws \Feralygon\Kit\Structure\Exceptions\CoercionFailed
	 * @return static|null
	 * <p>The given value coerced into an instance.<br>
	 * If nullable, then <code>null</code> may also be returned.</p>
	 */
	final public static function coerce(
		$value, bool $clone = false, ?callable $builder = null, bool $nullable = false
	): ?Structure
	{
		self::processCoercion($value, $clone, $builder, $nullable);
		return $value;
	}
	
	/**
	 * Process the coercion of a given value into an instance.
	 * 
	 * Only the following types and formats can be coerced into an instance:<br>
	 * &nbsp; &#8226; &nbsp; <code>null</code>, a string or an instance;<br>
	 * &nbsp; &#8226; &nbsp; an array of properties, given as <samp>name => value</samp> pairs;<br>
	 * &nbsp; &#8226; &nbsp; an object implementing the <code>Feralygon\Kit\Interfaces\Arrayable</code> interface.
	 * 
	 * @since 1.0.0
	 * @see \Feralygon\Kit\Interfaces\Arrayable
	 * @param mixed $value [reference]
	 * <p>The value to process (validate and sanitize).</p>
	 * @param bool $clone [default = false]
	 * <p>If an instance is given, then clone it into a new one with the same properties.</p>
	 * @param callable|null $builder [default = null]
	 * <p>The function to use to build an instance.<br>
	 * It is expected to be compatible with the following signature:<br>
	 * <br>
	 * <code>function (array $properties): Feralygon\Kit\Structure</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>array $properties</b></code><br>
	 * &nbsp; &nbsp; &nbsp; The properties to build with, as <samp>name => value</samp> pairs.<br>
	 * &nbsp; &nbsp; &nbsp; Required properties may also be given as an array of values 
	 * (<samp>[value1, value2, ...]</samp>), in the same order as how these properties were first declared.<br>
	 * <br>
	 * Return: <code><b>Feralygon\Kit\Structure</b></code><br>
	 * The built instance.</p>
	 * @param bool $nullable [default = false]
	 * <p>Allow the given value to coerce as <code>null</code>.</p>
	 * @param bool $no_throw [default = false]
	 * <p>Do not throw an exception.</p>
	 * @throws \Feralygon\Kit\Structure\Exceptions\CoercionFailed
	 * @return bool
	 * <p>Boolean <code>true</code> if the given value was successfully coerced into an instance.</p>
	 */
	final public static function processCoercion(
		&$value, bool $clone = false, ?callable $builder = null, bool $nullable = false, bool $no_throw = false
	): bool
	{
		//builder
		if (isset($builder)) {
			UCall::assert('builder', $builder, function (array $properties): Structure {});
		} else {
			$builder = [static::class, 'build'];
		}
		
		//coerce
		try {
			if (!isset($value) && $nullable) {
				return true;
			} elseif (!isset($value) || is_string($value) || is_array($value)) {
				$properties = is_string($value) ? static::getStringProperties($value) : $value ?? [];
				$value = UType::coerceObject($builder($properties), static::class);
				return true;
			} elseif (is_object($value)) {
				$instance = $value;
				if ($instance instanceof Structure) {
					if (!UType::isA($instance, static::class)) {
						$value = UType::coerceObject($builder($instance->getAll()), static::class);
					} elseif ($clone) {
						$value = $value->clone();
					}
					return true;
				} elseif (UData::evaluate($instance)) {
					$value = UType::coerceObject($builder($instance), static::class);
					return true;
				}
			}
		} catch (\Exception $exception) {
			if ($no_throw) {
				return false;
			}
			throw new Exceptions\CoercionFailed([
				'value' => $value,
				'structure' => static::class,
				'error_code' => Exceptions\CoercionFailed::ERROR_CODE_BUILD_EXCEPTION,
				'error_message' => $exception->getMessage()
			]);
		}
		
		//finish
		if ($no_throw) {
			return false;
		}
		throw new Exceptions\CoercionFailed([
			'value' => $value,
			'structure' => static::class,
			'error_code' => Exceptions\CoercionFailed::ERROR_CODE_INVALID_TYPE,
			'error_message' => "Only the following types and formats can be coerced into an instance:\n" . 
				" - null, a string or an instance;\n" . 
				" - an array of properties, given as \"name => value\" pairs;\n" . 
				" - an object implementing the \"Feralygon\\Kit\\Interfaces\\Arrayable\" interface."
		]);
	}
}
