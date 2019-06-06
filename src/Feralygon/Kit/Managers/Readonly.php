<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Managers;

use Feralygon\Kit\Manager;
use Feralygon\Kit\Root\System;
use Feralygon\Kit\Root\System\Enumerations\DumpVerbosityLevel as EDumpVerbosityLevel;
use Feralygon\Kit\Utilities\Call as UCall;

/**
 * This manager handles the read-only state and the resulting callback functions of an object.
 * 
 * @since 1.0.0
 */
class Readonly extends Manager
{
	//Private properties
	/** @var object */
	private $owner;
	
	/** @var bool */
	private $enabled = false;
	
	/** @var bool */
	private $recursive = false;
	
	/** @var \Closure[] */
	private $callbacks = [];
	
	
	
	//Final public magic methods
	/**
	 * Instantiate class.
	 * 
	 * @since 1.0.0
	 * @param object $owner
	 * <p>The owner object.</p>
	 */
	final public function __construct(object $owner)
	{
		$this->owner = $owner;
	}
	
	/**
	 * Get debug info.
	 * 
	 * @since 1.0.0
	 * @return array
	 * <p>The debug info.</p>
	 */
	final public function __debugInfo(): array
	{
		return $this->getDebugInfo();
	}
	
	

	//Public methods
	/**
	 * Get debug info.
	 * 
	 * @since 1.0.0
	 * @see https://www.php.net/manual/en/language.oop5.magic.php#object.debuginfo
	 * @return array
	 * <p>The debug info.</p>
	 */
	public function getDebugInfo(): array
	{
		if (System::getDumpVerbosityLevel() <= EDumpVerbosityLevel::MEDIUM) {
			return [
				'enabled' => $this->enabled,
				'recursive' => $this->recursive
			];
		}
		return (array)$this;
	}
	
	
	
	//Final public methods
	/**
	 * Get owner object.
	 * 
	 * @since 1.0.0
	 * @return object
	 * <p>The owner object.</p>
	 */
	final public function getOwner(): object
	{
		return $this->owner;
	}
	
	/**
	 * Check if is enabled.
	 * 
	 * @since 1.0.0
	 * @param bool $recursive [default = false]
	 * <p>Check if it has been recursively enabled.</p>
	 * @return bool
	 * <p>Boolean <code>true</code> if is enabled.</p>
	 */
	final public function isEnabled(bool $recursive = false): bool
	{
		return $this->enabled && (!$recursive || $this->recursive);
	}
	
	/**
	 * Enable.
	 * 
	 * @since 1.0.0
	 * @param bool $recursive [default = false]
	 * <p>Enable recursively.<br>
	 * <br>
	 * Any potential recursion may only be implemented in the callback functions.</p>
	 * @return $this
	 * <p>This instance, for chaining purposes.</p>
	 */
	final public function enable(bool $recursive = false): Readonly
	{
		if (!$this->isEnabled($recursive)) {
			foreach ($this->callbacks as $callback) {
				$callback($recursive);
			}
			$this->enabled = true;
			$this->recursive = $recursive;
		}
		return $this;
	}
	
	/**
	 * Add callback function.
	 * 
	 * All callback functions are called upon enablement.<br>
	 * <br>
	 * This method may only be called before enablement.
	 * 
	 * @since 1.0.0
	 * @param callable $callback
	 * <p>The callback function to add.<br>
	 * It is expected to be compatible with the following signature:<br>
	 * <br>
	 * <code>function (bool $recursive): void</code><br>
	 * <br>
	 * Parameters:<br>
	 * &nbsp; &#8226; &nbsp; <code><b>bool $recursive</b></code><br>
	 * &nbsp; &nbsp; &nbsp; Enable recursively.</p>
	 * @return $this
	 * <p>This instance, for chaining purposes.</p>
	 */
	final public function addCallback(callable $callback): Readonly
	{
		UCall::guard(!$this->enabled, [
			'hint_message' => "This method may only be called before enablement, in manager with owner {{owner}}.",
			'parameters' => ['owner' => $this->owner]
		]);
		UCall::assert('callback', $callback, function (bool $recursive): void {});
		$this->callbacks[] = \Closure::fromCallable($callback);
		return $this;
	}
	
	/**
	 * Guard the current function or method in the stack from being called after enablement.
	 * 
	 * @since 1.0.0
	 * @param int $stack_offset [default = 0]
	 * <p>The stack offset to use.<br>
	 * It must be greater than or equal to <code>0</code>.</p>
	 * @return void
	 */
	final public function guardCall(int $stack_offset = 0): void
	{
		UCall::guard(!$this->enabled, [
			'error_message' => "This method cannot be called as this object is currently set as read-only.",
			'stack_offset' => $stack_offset + 1
		]);
	}
}
