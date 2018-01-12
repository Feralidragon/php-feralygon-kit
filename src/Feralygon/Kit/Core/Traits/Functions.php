<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Traits;

use Feralygon\Kit\Core\Traits\Functions\Exceptions;
use Feralygon\Kit\Core\Utilities\Call as UCall;
use Feralygon\Kit\Root\System;

/**
 * Core functions trait.
 * 
 * This trait enables the support for a separate layer of custom functions in a class.<br>
 * All these functions have their signatures validated, and are meant to be bound to existing functions or methods.<br>
 * <br>
 * They may also be set as bind-once, so that any given already bound function with the same name cannot be rebound to another.
 * 
 * @since 1.0.0
 */
trait Functions
{
	//Private properties
	/** @var \Closure[] */
	private $functions = [];
	
	/** @var bool */
	private $functions_initialized = false;
	
	/** @var \Closure|null */
	private $functions_templater = null;
	
	/** @var bool */
	private $functions_bindonce = false;
	
	
	
	//Final public methods
	/**
	 * Bind a given function to a given name.
	 * 
	 * @since 1.0.0
	 * @param string $name <p>The function name to bind to.</p>
	 * @param callable $function <p>The function to bind.</p>
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionsNotInitialized
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionAlreadyBound
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionNotFound
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\InvalidFunctionSignature
	 * @return $this <p>This instance, for chaining purposes.</p>
	 */
	final public function bind(string $name, callable $function)
	{
		//validate
		if (!$this->functions_initialized) {
			throw new Exceptions\FunctionsNotInitialized(['object' => $this]);
		} elseif ($this->functions_bindonce && isset($this->functions[$name])) {
			throw new Exceptions\FunctionAlreadyBound(['object' => $this, 'name' => $name]);
		}
		
		//template
		$template = ($this->functions_templater)($name);
		if (!isset($template)) {
			throw new Exceptions\FunctionNotFound(['object' => $this, 'name' => $name]);
		} elseif (System::getEnvironment()->isDebug()) {
			$function_signature = UCall::signature($function);
			$template_signature = UCall::signature($template);
			if ($function_signature !== $template_signature) {
				throw new Exceptions\InvalidFunctionSignature([
					'object' => $this,
					'name' => $name,
					'function' => $function,
					'template' => $template,
					'signature' => $function_signature,
					'template_signature' => $template_signature
				]);
			}
		}
		
		//bind
		$this->functions[$name] = \Closure::fromCallable($function);
		
		//return
		return $this;
	}
	
	
	
	//Final protected methods
	/**
	 * Call function with a given name.
	 * 
	 * @since 1.0.0
	 * @param string $name <p>The function name to call.</p>
	 * @param mixed $arguments [variadic] <p>The function arguments to call with.</p>
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionsNotInitialized
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionNotFound
	 * @return mixed <p>The returning value from the called function with the given name.</p>
	 */
	final protected function call(string $name, ...$arguments)
	{
		if (!$this->functions_initialized) {
			throw new Exceptions\FunctionsNotInitialized(['object' => $this]);
		} elseif (!isset($this->functions[$name])) {
			throw new Exceptions\FunctionNotFound(['object' => $this, 'name' => $name]);
		}
		return ($this->functions[$name])(...$arguments);
	}
	
	
	
	//Final private methods
	/**
	 * Initialize functions with a given templater function.
	 * 
	 * @since 1.0.0
	 * @param callable $templater <p>The function to retrieve the function template for a given name.<br>
	 * The expected function signature is represented as:<br><br>
	 * <code>function (string $name) : ?callable</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>string $name</b></code> : The function name to retrieve the template for.<br>
	 * <br>
	 * Return: <code><b>callable|null</b></code><br>
	 * The function template for the given name or <code>null</code> if none exists.
	 * </p>
	 * @param bool $bindonce [default = false] <p>Set functions as bind-once.</p>
	 * @throws \Feralygon\Kit\Core\Traits\Functions\Exceptions\FunctionsAlreadyInitialized
	 * @return void
	 */
	final private function initializeFunctions(callable $templater, bool $bindonce = false) : void
	{
		//check
		if ($this->functions_initialized) {
			throw new Exceptions\FunctionsAlreadyInitialized(['object' => $this]);
		}
		
		//templater
		UCall::assertSignature($templater, function (string $name) : ?callable {}, true);
		$this->functions_templater = \Closure::fromCallable($templater);
		
		//finish
		$this->functions_initialized = true;
		$this->functions_bindonce = $bindonce;
	}
}
