<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Core\Utilities;

use Feralygon\Kit\Core\Utility;
use Feralygon\Kit\Core\Utilities\Data\Exceptions;

/**
 * Core data utility class.
 * 
 * This utility implements a set of methods used to manipulate data structures in the form of PHP arrays.
 * 
 * @since 1.0.0
 * @see https://php.net/manual/en/language.types.array.php
 */
final class Data extends Utility
{
	//Public constants
	/** Associative union merge (flag). */
	public const MERGE_ASSOC_UNION = 0x001;
	
	/** Associative left merge (flag). */
	public const MERGE_ASSOC_LEFT = 0x002;
	
	/** Non-associative associative merge (flag). */
	public const MERGE_NONASSOC_ASSOC = 0x004;
	
	/** Non-associative append merge (flag). */
	public const MERGE_NONASSOC_APPEND = 0x008;
	
	/** Non-associative union merge (flag). */
	public const MERGE_NONASSOC_UNION = 0x010;
	
	/** Non-associative left merge (flag). */
	public const MERGE_NONASSOC_LEFT = 0x020;
	
	/** Non-associative swap merge (flag). */
	public const MERGE_NONASSOC_SWAP = 0x040;
	
	/** Non-associative keep merge (flag). */
	public const MERGE_NONASSOC_KEEP = 0x080;
	
	/** Non-associative unique merge (flag). */
	public const MERGE_NONASSOC_UNIQUE = 0x100;
	
	/** Associative exclude unique (flag). */
	public const UNIQUE_ASSOC_EXCLUDE = 0x01;
	
	/** Non-associative associative unique (flag). */
	public const UNIQUE_NONASSOC_ASSOC = 0x02;
	
	/** Non-associative exclude unique (flag). */
	public const UNIQUE_NONASSOC_EXCLUDE = 0x04;
	
	/** Reverse sort (flag). */
	public const SORT_REVERSE = 0x01;
	
	/** Associative exclude sort (flag). */
	public const SORT_ASSOC_EXCLUDE = 0x04;
	
	/** Non-associative associative sort (flag). */
	public const SORT_NONASSOC_ASSOC = 0x08;
	
	/** Non-associative exclude sort (flag). */
	public const SORT_NONASSOC_EXCLUDE = 0x10;
	
	/** Inverse filter (flag). */
	public const FILTER_INVERSE = 0x01;
	
	/** Empty filter (flag). */
	public const FILTER_EMPTY = 0x02;
	
	/** Associative exclude filter (flag). */
	public const FILTER_ASSOC_EXCLUDE = 0x04;
	
	/** Non-associative associative filter (flag). */
	public const FILTER_NONASSOC_ASSOC = 0x08;
	
	/** Non-associative exclude filter (flag). */
	public const FILTER_NONASSOC_EXCLUDE = 0x10;
	
	/** Inverse trim (flag). */
	public const TRIM_INVERSE = 0x01;
	
	/** Left trim (flag). */
	public const TRIM_LEFT = 0x02;
	
	/** Right trim (flag). */
	public const TRIM_RIGHT = 0x04;
	
	/** Empty trim (flag). */
	public const TRIM_EMPTY = 0x08;
	
	/** Associative exclude trim (flag). */
	public const TRIM_ASSOC_EXCLUDE = 0x10;
	
	/** Non-associative associative trim (flag). */
	public const TRIM_NONASSOC_ASSOC = 0x20;
	
	/** Non-associative exclude trim (flag). */
	public const TRIM_NONASSOC_EXCLUDE = 0x40;
	
	/** Associative exclude intersection (flag). */
	public const INTERSECT_ASSOC_EXCLUDE = 0x01;
	
	/** Non-associative associative intersection (flag). */
	public const INTERSECT_NONASSOC_ASSOC = 0x02;
	
	/** Non-associative exclude intersection (flag). */
	public const INTERSECT_NONASSOC_EXCLUDE = 0x04;

	/** Associative exclude difference (flag). */
	public const DIFF_ASSOC_EXCLUDE = 0x01;
	
	/** Non-associative associative difference (flag). */
	public const DIFF_NONASSOC_ASSOC = 0x02;
	
	/** Non-associative exclude difference (flag). */
	public const DIFF_NONASSOC_EXCLUDE = 0x04;

	/** Associative exclude shuffle (flag). */
	public const SHUFFLE_ASSOC_EXCLUDE = 0x01;
	
	/** Non-associative associative shuffle (flag). */
	public const SHUFFLE_NONASSOC_ASSOC = 0x02;
	
	/** Non-associative exclude shuffle (flag). */
	public const SHUFFLE_NONASSOC_EXCLUDE = 0x04;
	
	/** Associative exclude alignment (flag). */
	public const ALIGN_ASSOC_EXCLUDE = 0x01;
	
	/** Non-associative exclude alignment (flag). */
	public const ALIGN_NONASSOC_EXCLUDE = 0x02;
	
	
	
	//Private constants
	/** Non-associative required merge flags mask. */
	private const MERGE_NONASSOC_REQUIRED_MASK = self::MERGE_NONASSOC_ASSOC | self::MERGE_NONASSOC_APPEND | self::MERGE_NONASSOC_UNION | self::MERGE_NONASSOC_LEFT | self::MERGE_NONASSOC_SWAP | self::MERGE_NONASSOC_KEEP;
	
	/** Keyfy maximum raw string length before transforming into a hash. */
	private const KEYFY_MAX_RAW_STRING_LENGTH = 40;
	
	/** Keyfy maximum raw array length before transforming into a hash. */
	private const KEYFY_MAX_RAW_ARRAY_LENGTH = 40;
	
	
	
	//Final public static methods
	/**
	 * Check if a given array is associative.
	 * 
	 * The given array is only considered to be associative when it's not empty and 
	 * its keys are not consecutive integers starting from <code>0</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to check.</p>
	 * @return bool <p>Boolean <code>true</code> if the given array is associative.</p>
	 */
	final public static function isAssociative(array $array) : bool
	{
		return !empty($array) && (!array_key_exists(0, $array) || !array_key_exists(count($array) - 1, $array) || (array_keys($array) !== range(0, count($array) - 1)));
	}
	
	/**
	 * Transform a given value into an unique key.
	 * 
	 * The returning key is not intended to be restored to its original value (and cannot in most cases), given that this function 
	 * is only meant to efficiently produce a key which can be used in associative arrays for strict mapping and data comparisons.
	 * 
	 * @since 1.0.0
	 * @param mixed $value <p>The value to transform.</p>
	 * @param bool|null $safe [reference output] [default = null] <p>The safety indicator which, if set to <code>true</code>, 
	 * indicates that the generated key may be used for longer term purposes, such as internal cache keys.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\KeyfyUnsupportedValueType
	 * @return string <p>An unique key from the given value.</p>
	 */
	final public static function keyfy($value, ?bool &$safe = null) : string
	{
		$safe = null;
		if (!isset($value)) {
			$safe = true;
			return 'n';
		} elseif (is_string($value)) {
			$safe = true;
			return strlen($value) > self::KEYFY_MAX_RAW_STRING_LENGTH ? 'S:' . sha1($value) : "s:{$value}";
		} elseif (is_int($value)) {
			$safe = true;
			return "i:{$value}";
		} elseif (is_float($value)) {
			$safe = true;
			return "f:{$value}";
		} elseif (is_bool($value)) {
			$safe = true;
			return 'b:' . (int)$value;
		} elseif (is_resource($value)) {
			$safe = false;
			return 'R:' . (int)$value;
		} elseif (is_object($value)) {
			$safe = false;
			return 'O:' . spl_object_hash($value);
		} elseif (is_array($value)) {
			$array_safe = true;
			foreach ($value as &$v) {
				$v = self::keyfy($v, $s);
				$array_safe = $array_safe && $s;
			}
			unset($v);
			$safe = $array_safe;
			$value = json_encode($value);
			return strlen($value) > self::KEYFY_MAX_RAW_ARRAY_LENGTH ? 'A:' . sha1($value) : "a:{$value}";
		}
		throw new Exceptions\KeyfyUnsupportedValueType(['value' => $value, 'type' => gettype($value)]);
	}
	
	/**
	 * Merge two given arrays recursively.
	 * 
	 * By omission, non-associative arrays are joined together and their keys are recalculated, 
	 * whereas with associative arrays all the keys from the second array are inserted into the first one, 
	 * which are replaced if already existent.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-merge.php
	 * @param array $array1 <p>The first array, to merge into.</p>
	 * @param array $array2 <p>The second array, to merge with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the merging at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x000] <p>The merge bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_ASSOC_UNION</code> : Merge associative arrays using the union operation, in other words,
	 * with this flag keys present in the first array won't get replaced by the same keys present in the second.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_ASSOC_LEFT</code> : Merge associative arrays but only from the left, in other words,
	 * with this flag only the keys present in the first array will remain, while any keys exclusively present in the second will be discarded.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_APPEND</code> : Merge non-associative arrays by appending the second to the first.<br>
	 * This the default flag used if no non-associative flags are set.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_ASSOC</code> : Merge non-associative arrays associatively.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_UNION</code> : Merge non-associative arrays associatively by using the union operation, in other words,
	 * with this flag keys present in the first array won't get replaced by the same keys present in the second for non-associative arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_LEFT</code> : Merge non-associative arrays associatively but only from the left, in other words,
	 * with this flag only the keys present in the first array will remain, while any keys exclusively present in the second will be discarded for non-associative arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_SWAP</code> : Merge non-associative arrays by swapping the second entirely with the first.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_KEEP</code> : Merge non-associative arrays by keeping the first entirely.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::MERGE_NONASSOC_UNIQUE</code> : When merging non-associative arrays, ensure that only unique values are present in the merged array.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The merged array from the two given ones.</p>
	 */
	final public static function merge(array $array1, array $array2, ?int $depth = null, int $flags = 0x000) : array
	{
		//initialize
		if (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$is_assoc = self::isAssociative($array1) || self::isAssociative($array2);
		$is_union = ($is_assoc && ($flags & self::MERGE_ASSOC_UNION)) || (!$is_assoc && ($flags & self::MERGE_NONASSOC_UNION));
		$is_left = ($is_assoc && ($flags & self::MERGE_ASSOC_LEFT)) || (!$is_assoc && ($flags & self::MERGE_NONASSOC_LEFT));
		$is_unique = !$is_assoc && ($flags & self::MERGE_NONASSOC_UNIQUE);
		if (!($flags & self::MERGE_NONASSOC_REQUIRED_MASK)) {
			$flags |= self::MERGE_NONASSOC_APPEND;
		}
		
		//non-associative
		if (!$is_assoc && !($flags & (self::MERGE_NONASSOC_ASSOC | self::MERGE_NONASSOC_UNION | self::MERGE_NONASSOC_LEFT))) {
			$array = [];
			if ($flags & self::MERGE_NONASSOC_SWAP) {
				$array = $array2;
			} elseif ($flags & self::MERGE_NONASSOC_KEEP) {
				$array = $array1;
			} elseif ($flags & self::MERGE_NONASSOC_APPEND) {
				$array = array_merge($array1, $array2);
			}
			return $is_unique ? self::unique($array, 0) : $array;
		}
		
		//empty
		if (empty($array1) || empty($array2)) {
			if (empty($array1)) {
				return $is_left ? [] : ($is_unique ? self::unique($array2, 0, self::UNIQUE_NONASSOC_ASSOC) : $array2);
			}
			return $is_unique ? self::unique($array1, 0, self::UNIQUE_NONASSOC_ASSOC) : $array1;
		}
		
		//union
		if ($is_union && $depth === 0) {
			$array = $array1;
			if (!$is_left) {
				$array += $array2;
			}
			return $is_unique ? self::unique($array, 0, self::UNIQUE_NONASSOC_ASSOC) : $array;
		}
		
		//replace
		if (empty($depth) && !$is_union && !$is_left && !$is_unique && ($flags & self::MERGE_NONASSOC_ASSOC)) {
			return isset($depth) ? array_replace($array1, $array2) : array_replace_recursive($array1, $array2);
		}
		
		//merge			
		$array = $array1;
		$next_depth = isset($depth) ? $depth - 1 : null;
		foreach ($array as $k => &$v) {
			if (array_key_exists($k, $array2)) {
				if (is_array($v) && is_array($array2[$k]) && (!isset($next_depth) || $next_depth >= 0)) {
					$v = self::merge($v, $array2[$k], $next_depth, $flags);
				} elseif (!$is_union) {
					$v = $array2[$k];
				}
			}
		}
		unset($v);
		
		//finish
		if (!$is_left) {
			$array += $array2;
		}
		if ($is_unique) {
			$array = self::unique($array, 0, self::UNIQUE_NONASSOC_ASSOC);
		}
		
		//return
		return $array;
	}
	
	/**
	 * Remove duplicated non-array values from a given array strictly and recursively.
	 * 
	 * The removal is performed in such a way that only strictly unique values are present in the returning array, 
	 * as not only the values are considered, but also their types as well.<br>
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact.<br>
	 * <br>
	 * Since the function is recursive and only handles non-array values, values which are themselves arrays are not affected, 
	 * therefore it's possible to have two or more arrays with exactly the same data in the returning array.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-unique.php
	 * @param array $array <p>The array to remove from.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the removal at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The unique bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::UNIQUE_ASSOC_EXCLUDE</code> : Exclude associative arrays from the removal of duplicates.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::UNIQUE_NONASSOC_ASSOC</code> : Remove duplicates from non-associative arrays associatively, in other words, keep the keys intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::UNIQUE_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from the removal of duplicates.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The given array without duplicated values.</p>
	 */
	final public static function unique(array $array, ?int $depth = null, int $flags = 0x00) : array
	{
		//unique
		$is_assoc = self::isAssociative($array);
		if (($is_assoc && !($flags & self::UNIQUE_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::UNIQUE_NONASSOC_EXCLUDE))) {
			$map = [];
			foreach ($array as $k => $v) {
				$key = is_array($v) ? "a:{$k}" : self::keyfy($v);
				if (isset($map[$key])) {
					unset($array[$k]);
				} else {
					$map[$key] = true;
				}
			}
			unset($map);
		}
		
		//non-associative
		if (!$is_assoc && !($flags & self::UNIQUE_NONASSOC_ASSOC)) {
			$array = array_values($array);
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		
		//recursion
		$next_depth = isset($depth) ? $depth - 1 : null;
		foreach ($array as &$v) {
			if (is_array($v)) {
				$v = self::unique($v, $next_depth, $flags);
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Sort a given array recursively.
	 * 
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact, 
	 * and the sorting is performed in ascending order.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.sort.php
	 * @param array $array <p>The array to sort.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the sorting at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The sort bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_REVERSE</code> : Sort array in reverse (descending order).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_ASSOC_EXCLUDE</code> : Exclude associative arrays from sorting.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_NONASSOC_ASSOC</code> : Sort non-associative arrays associatively, in other words, keep the keys intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from sorting.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The sorted array.</p>
	 */
	final public static function sort(array $array, ?int $depth = null, int $flags = 0x00) : array
	{
		//sort
		$is_assoc = self::isAssociative($array);
		if (($is_assoc && !($flags & self::SORT_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::SORT_NONASSOC_EXCLUDE))) {
			if ($is_assoc || ($flags & self::SORT_NONASSOC_ASSOC)) {
				if ($flags & self::SORT_REVERSE) {
					arsort($array);
				} else {
					asort($array);
				}
			} else {
				if ($flags & self::SORT_REVERSE) {
					rsort($array);
				} else {
					sort($array);
				}
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as &$v) {
			if (is_array($v)) {
				$v = self::sort($v, $next_depth, $flags);
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Sort a given array recursively by key.
	 * 
	 * By omission, the sorting is performed in ascending order.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.ksort.php
	 * @param array $array <p>The array to sort.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the sorting at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The sort bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_REVERSE</code> : Sort array in reverse (descending order).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_ASSOC_EXCLUDE</code> : Exclude associative arrays from sorting.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SORT_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from sorting.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The sorted array by key.</p>
	 */
	final public static function ksort(array $array, ?int $depth = null, int $flags = 0x00) : array
	{
		//sort
		$is_assoc = self::isAssociative($array);
		if (($is_assoc && !($flags & self::SORT_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::SORT_NONASSOC_EXCLUDE))) {
			if ($flags & self::SORT_REVERSE) {
				krsort($array);
			} else {
				ksort($array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as &$v) {
			if (is_array($v)) {
				$v = self::ksort($v, $next_depth, $flags);
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Filter a given array strictly and recursively from a given set of non-array values.
	 * 
	 * The filtering is performed in such a way that the given values are strictly removed from the returning array, 
	 * as not only the values are considered, but also their types as well.<br>
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact.<br>
	 * <br>
	 * Since the function is recursive and only handles non-array values, values which are themselves arrays are not affected.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-filter.php
	 * @param array $array <p>The array to filter.</p>
	 * @param array $values <p>The values to filter from.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the filtering at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The filter bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_INVERSE</code> : Filter array inversely, in other words, 
	 * strictly filter array from all non-array values but the given ones.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_EMPTY</code> : Filter array from empty arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_ASSOC_EXCLUDE</code> : Exclude associative arrays from filtering.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_NONASSOC_ASSOC</code> : Filter non-associative arrays associatively, in other words, keep the keys intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from filtering.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The filtered array from the given set of non-array values.</p>
	 */
	final public static function filter(array $array, array $values, ?int $depth = null, int $flags = 0x00) : array
	{
		//filter
		$is_assoc = self::isAssociative($array);
		$is_empty = (bool)($flags & self::FILTER_EMPTY);
		if (($is_assoc && !($flags & self::FILTER_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::FILTER_NONASSOC_EXCLUDE))) {
			//iterate
			$is_inverse = (bool)($flags & self::FILTER_INVERSE);
			foreach ($array as $k => $v) {
				if (($is_empty && is_array($v) && empty($v)) || (!is_array($v) && in_array($v, $values, true) !== $is_inverse)) {
					unset($array[$k]);
				}
			}
			
			//non-associative
			if (!$is_assoc && !($flags & self::FILTER_NONASSOC_ASSOC)) {
				$array = array_values($array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as $k => &$v) {
			if (is_array($v)) {
				$v = self::filter($v, $values, $next_depth, $flags);
				if ($is_empty && empty($v)) {
					unset($array[$k]);
				}
			}
		}
		unset($v);
			
		//non-associative
		if (!$is_assoc && !($flags & self::FILTER_NONASSOC_ASSOC)) {
			$array = array_values($array);
		}
		
		//return
		return $array;
	}
	
	/**
	 * Filter a given array recursively from a given set of keys.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to filter.</p>
	 * @param array $keys <p>The keys to filter from.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the filtering at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The filter bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_INVERSE</code> : Filter array inversely, in other words,
	 * filter array from all keys but the given ones.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_EMPTY</code> : Filter array from empty arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_ASSOC_EXCLUDE</code> : Exclude associative arrays from filtering.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::FILTER_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from filtering.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The filtered array from the given set of keys.</p>
	 */
	final public static function kfilter(array $array, array $keys, ?int $depth = null, int $flags = 0x00) : array
	{
		//filter
		$is_assoc = self::isAssociative($array);
		$is_empty = (bool)($flags & self::FILTER_EMPTY);
		if (($is_assoc && !($flags & self::FILTER_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::FILTER_NONASSOC_EXCLUDE))) {
			$array = ($flags & self::FILTER_INVERSE) ? array_intersect_key($array, array_flip($keys)) : array_diff_key($array, array_flip($keys));
			if ($is_empty) {
				foreach ($array as $k => $v) {
					if (is_array($v) && empty($v)) {
						unset($array[$k]);
					}
				}
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as $k => &$v) {
			if (is_array($v)) {
				$v = self::kfilter($v, $keys, $next_depth, $flags);
				if ($is_empty && empty($v)) {
					unset($array[$k]);
				}
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Trim a given array strictly and recursively from a given set of non-array values.
	 * 
	 * The trimming is performed in such a way that the given values are strictly trimmed out from the returning array, 
	 * as not only the values are considered, but also their types as well.<br>
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact.<br>
	 * <br>
	 * Since the function is recursive and only handles non-array values, values which are themselves arrays are not affected.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to trim.</p>
	 * @param array $values <p>The values to trim from.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the trimming at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The trim bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_INVERSE</code> : Trim array inversely, in other words, 
	 * strictly trim array from all non-array values but the given ones.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_LEFT</code> : Trim only the left side of the array (the first values).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_RIGHT</code> : Trim only the right side of the array (the last values).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_EMPTY</code> : Trim array from empty arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_ASSOC_EXCLUDE</code> : Exclude associative arrays from trimming.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_NONASSOC_ASSOC</code> : Trim non-associative arrays associatively, in other words, keep the keys intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from trimming.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The trimmed array from the given set of non-array values.</p>
	 */
	final public static function trim(array $array, array $values, ?int $depth = null, int $flags = 0x00) : array
	{
		//trim
		$is_assoc = self::isAssociative($array);
		$is_empty = (bool)($flags & self::TRIM_EMPTY);
		if (($is_assoc && !($flags & self::TRIM_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::TRIM_NONASSOC_EXCLUDE))) {
			//initialize
			if (!($flags & (self::TRIM_LEFT | self::TRIM_RIGHT))) {
				$flags |= self::TRIM_LEFT | self::TRIM_RIGHT;
			}
			$pipe_keys = [];
			$array_keys = array_keys($array);
			if ($flags & self::TRIM_LEFT) {
				$pipe_keys[] = $array_keys;
			}
			if ($flags & self::TRIM_RIGHT) {
				$pipe_keys[] = array_reverse($array_keys);
			}
			
			//iterate
			$is_inverse = (bool)($flags & self::TRIM_INVERSE);
			foreach ($pipe_keys as $pkeys) {
				foreach ($pkeys as $k) {
					$v = $array[$k];
					if ((!$is_empty || !is_array($v) || !empty($v)) && (is_array($v) || in_array($v, $values, true) === $is_inverse)) {
						break;
					}
					unset($array[$k]);
				}
			}
			
			//non-associative
			if (!$is_assoc && !($flags & self::TRIM_NONASSOC_ASSOC)) {
				$array = array_values($array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as $k => &$v) {
			if (is_array($v)) {
				$v = self::trim($v, $values, $next_depth, $flags);
				if ($is_empty && empty($v)) {
					unset($array[$k]);
				}
			}
		}
		unset($v);
			
		//non-associative
		if (!$is_assoc && !($flags & self::TRIM_NONASSOC_ASSOC)) {
			$array = array_values($array);
		}
		
		//return
		return $array;
	}
	
	/**
	 * Trim a given array recursively from a given set of keys.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to trim.</p>
	 * @param array $keys <p>The keys to trim from.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the trimming at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The trim bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_INVERSE</code> : Trim array inversely, in other words,
	 * trim array from all keys but the given ones.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_LEFT</code> : Trim only the left side of the array (the first keys).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_RIGHT</code> : Trim only the right side of the array (the last keys).<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_EMPTY</code> : Trim array from empty arrays.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_ASSOC_EXCLUDE</code> : Exclude associative arrays from trimming.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::TRIM_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from trimming.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The trimmed array from the given set of keys.</p>
	 */
	final public static function ktrim(array $array, array $keys, ?int $depth = null, int $flags = 0x00) : array
	{
		//trim
		$is_assoc = self::isAssociative($array);
		$is_empty = (bool)($flags & self::TRIM_EMPTY);
		if (($is_assoc && !($flags & self::TRIM_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::TRIM_NONASSOC_EXCLUDE))) {
			//initialize
			if (!($flags & (self::TRIM_LEFT | self::TRIM_RIGHT))) {
				$flags |= self::TRIM_LEFT | self::TRIM_RIGHT;
			}
			$pipe_keys = [];
			$array_keys = array_keys($array);
			if ($flags & self::TRIM_LEFT) {
				$pipe_keys[] = $array_keys;
			}
			if ($flags & self::TRIM_RIGHT) {
				$pipe_keys[] = array_reverse($array_keys);
			}
			
			//iterate
			$keys_map = array_flip($keys);
			$is_inverse = (bool)($flags & self::TRIM_INVERSE);
			foreach ($pipe_keys as $pkeys) {
				foreach ($pkeys as $k) {
					if (isset($keys_map[$k]) === $is_inverse && (!$is_empty || !is_array($array[$k]) || !empty($array[$k]))) {
						break;
					}
					unset($array[$k]);
				}
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as $k => &$v) {
			if (is_array($v)) {
				$v = self::ktrim($v, $keys, $next_depth, $flags);
				if ($is_empty && empty($v)) {
					unset($array[$k]);
				}
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Intersect two given arrays strictly and recursively.
	 * 
	 * The intersection is performed in such a way that the returning array is only composed by the values from the first array 
	 * which also strictly exist in the second one as well, as not only the values are considered, but also their types as well.<br>
	 * <br>
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact 
	 * and the keys themselves are also considered for the intersection.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-intersect.php
	 * @param array $array1 <p>The first array, to intersect from.</p>
	 * @param array $array2 <p>The second array, to intersect with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the intersection at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The intersection bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::INTERSECT_ASSOC_EXCLUDE</code> : Exclude associative arrays from intersecting.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::INTERSECT_NONASSOC_ASSOC</code> : Intersect non-associative arrays associatively, in other words,
	 * consider the keys in the intersection and keep them intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::INTERSECT_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from intersecting.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The intersected array from the two given arrays.</p>
	 */
	final public static function intersect(array $array1, array $array2, ?int $depth = null, int $flags = 0x00) : array
	{
		//empty arrays
		if (empty($array1) || empty($array2)) {
			return [];
		}
		
		//intersect
		$is_assoc = self::isAssociative($array1) || self::isAssociative($array2);
		if (($is_assoc && !($flags & self::INTERSECT_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::INTERSECT_NONASSOC_EXCLUDE))) {
			//associative
			if ($is_assoc || ($flags & self::INTERSECT_NONASSOC_ASSOC)) {
				$array1 = array_intersect_key($array1, $array2);
				foreach ($array1 as $k => $v) {
					if (!is_array($v) && $v !== $array2[$k]) {
						unset($array1[$k]);
					}
				}
				
			//non-associative
			} else {
				//mapping
				$maps = [[], []];
				$arrays = [$array1, $array2];
				foreach ($arrays as $i => $array) {
					foreach ($array as $k => $v) {
						$maps[$i][is_array($v) ? "ak:{$k}" : self::keyfy($v)][$k] = true;
					}
				}
				
				//intersection
				$array = [];
				foreach (array_intersect_key($maps[0], $maps[1]) as $map) {
					$array += array_intersect_key($array1, $map);
				}
				$array1 = array_values($array);
				unset($maps, $arrays, $array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array1;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array1 as $k => &$v) {
			if (is_array($v)) {
				if (isset($array2[$k]) && is_array($array2[$k])) {
					$v = self::intersect($v, $array2[$k], $next_depth, $flags);
					if (empty($v)) {
						unset($array1[$k]);
					}
				} else {
					unset($array1[$k]);
				}
			}
		}
		unset($v);
			
		//non-associative
		if (!$is_assoc && !($flags & self::INTERSECT_NONASSOC_ASSOC)) {
			$array1 = array_values($array1);
		}
		
		//return
		return $array1;
	}
	
	/**
	 * Intersect two given arrays recursively by key.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-intersect-key.php
	 * @param array $array1 <p>The first array, to intersect from.</p>
	 * @param array $array2 <p>The second array, to intersect with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the intersection at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The intersection bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::INTERSECT_ASSOC_EXCLUDE</code> : Exclude associative arrays from intersecting.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::INTERSECT_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from intersecting.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The intersected array by key from the two given arrays.</p>
	 */
	final public static function kintersect(array $array1, array $array2, ?int $depth = null, int $flags = 0x00) : array
	{
		//empty arrays
		if (empty($array1) || empty($array2)) {
			return [];
		}
		
		//intersect
		$is_assoc = self::isAssociative($array1) || self::isAssociative($array2);
		if (($is_assoc && !($flags & self::INTERSECT_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::INTERSECT_NONASSOC_EXCLUDE))) {
			$array1 = array_intersect_key($array1, $array2);
		}
		
		//depth
		if ($depth === 0) {
			return $array1;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array1 as $k => &$v) {
			if (is_array($v)) {
				if (isset($array2[$k]) && is_array($array2[$k])) {
					$v = self::kintersect($v, $array2[$k], $next_depth, $flags);
					if (empty($v)) {
						unset($array1[$k]);
					}
				} else {
					unset($array1[$k]);
				}
			}
		}
		unset($v);
		
		//return
		return $array1;
	}
	
	/**
	 * Differentiate two given arrays strictly and recursively.
	 * 
	 * The differentiation is performed in such a way that the returning array is only composed by the values from the first array
	 * which strictly do not exist in the second one, as not only the values are considered, but also their types as well.<br>
	 * <br>
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact
	 * and the keys themselves are also considered for the differentiation.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-diff.php
	 * @param array $array1 <p>The first array, to differentiate from.</p>
	 * @param array $array2 <p>The second array, to differentiate with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the differentiation at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The difference bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::DIFF_ASSOC_EXCLUDE</code> : Exclude associative arrays from differentiating.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::DIFF_NONASSOC_ASSOC</code> : Differentiate non-associative arrays associatively, in other words,
	 * consider the keys in the difference and keep them intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::DIFF_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from differentiating.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The differentiated array from the two given arrays.</p>
	 */
	final public static function diff(array $array1, array $array2, ?int $depth = null, int $flags = 0x00) : array
	{
		//empty arrays
		if (empty($array1) || empty($array2)) {
			return $array1;
		}
		
		//differenciate
		$is_assoc = self::isAssociative($array1) || self::isAssociative($array2);
		if (($is_assoc && !($flags & self::DIFF_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::DIFF_NONASSOC_EXCLUDE))) {
			//associative
			if ($is_assoc || ($flags & self::DIFF_NONASSOC_ASSOC)) {
				foreach ($array1 as $k => $v) {
					if (array_key_exists($k, $array2) && !is_array($v) && $v === $array2[$k]) {
						unset($array1[$k]);
					}
				}
				
			//non-associative
			} else {
				//mapping
				$maps = [[], []];
				$arrays = [$array1, $array2];
				foreach ($arrays as $i => $array) {
					foreach ($array as $k => $v) {
						$maps[$i][is_array($v) ? "ak:{$i}:{$k}" : self::keyfy($v)][$k] = true;
					}
				}
				
				//difference
				$array = [];
				foreach (array_diff_key($maps[0], $maps[1]) as $map) {
					$array += array_intersect_key($array1, $map);
				}
				$array1 = array_values($array);
				unset($maps, $arrays, $array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array1;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array1 as $k => &$v) {
			if (is_array($v) && isset($array2[$k]) && is_array($array2[$k])) {
				$v = self::diff($v, $array2[$k], $next_depth, $flags);
				if (empty($v)) {
					unset($array1[$k]);
				}
			}
		}
		unset($v);
			
		//non-associative
		if (!$is_assoc && !($flags & self::DIFF_NONASSOC_ASSOC)) {
			$array1 = array_values($array1);
		}
		
		//return
		return $array1;
	}
	
	/**
	 * Differentiate two given arrays recursively by key.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.array-diff-key.php
	 * @param array $array1 <p>The first array, to differentiate from.</p>
	 * @param array $array2 <p>The second array, to differentiate with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the differentiation at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The difference bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::DIFF_ASSOC_EXCLUDE</code> : Exclude associative arrays from differentiating.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::DIFF_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from differentiating.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The differentiated array by key from the two given arrays.</p>
	 */
	final public static function kdiff(array $array1, array $array2, ?int $depth = null, int $flags = 0x00) : array
	{
		//empty arrays
		if (empty($array1) || empty($array2)) {
			return $array1;
		}
		
		//differenciate
		$is_assoc = self::isAssociative($array1) || self::isAssociative($array2);
		if (($is_assoc && !($flags & self::DIFF_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::DIFF_NONASSOC_EXCLUDE))) {
			foreach ($array1 as $k => $v) {
				if (array_key_exists($k, $array2) && (!is_array($v) || !is_array($array2[$k]))) {
					unset($array1[$k]);
				}
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array1;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array1 as $k => &$v) {
			if (is_array($v) && isset($array2[$k]) && is_array($array2[$k])) {
				$v = self::kdiff($v, $array2[$k], $next_depth, $flags);
				if (empty($v)) {
					unset($array1[$k]);
				}
			}
		}
		unset($v);
		
		//return
		return $array1;
	}
	
	/**
	 * Shuffle a given array recursively.
	 * 
	 * By omission, in non-associative arrays the keys are recalculated, whereas in associative arrays the keys are kept intact.
	 * 
	 * @since 1.0.0
	 * @see https://php.net/manual/en/function.shuffle.php
	 * @param array $array <p>The array to shuffle.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the shuffling at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The shuffle bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SHUFFLE_ASSOC_EXCLUDE</code> : Exclude associative arrays from shuffling.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SHUFFLE_NONASSOC_ASSOC</code> : Shuffle non-associative arrays associatively, in other words, keep the keys intact.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::SHUFFLE_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from shuffling.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The shuffled array.</p>
	 */
	final public static function shuffle(array $array, ?int $depth = null, int $flags = 0x00) : array
	{
		//shuffle
		$is_assoc = self::isAssociative($array);
		if (($is_assoc && !($flags & self::SHUFFLE_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::SHUFFLE_NONASSOC_EXCLUDE))) {
			if ($is_assoc || ($flags & self::SHUFFLE_NONASSOC_ASSOC)) {
				$keys = array_keys($array);
				shuffle($keys);
				$array = self::align($array, $keys, 0);
			} else {
				shuffle($array);
			}
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as &$v) {
			if (is_array($v)) {
				$v = self::shuffle($v, $next_depth, $flags);
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Align a given array with a set of given keys recursively.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to align.</p>
	 * @param array $keys <p>The keys to align with.</p>
	 * @param int|null $depth [default = null] <p>The recursive depth limit to stop the alignment at.<br>
	 * If not set, then no limit is applied, otherwise it must be greater than or equal to <code>0</code>.</p>
	 * @param int $flags [default = 0x00] <p>The alignment bitwise flags, which can be any combination of the following:<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::ALIGN_ASSOC_EXCLUDE</code> : Exclude associative arrays from aligning.<br><br>
	 * &nbsp; &#8226; &nbsp; <code>self::ALIGN_NONASSOC_EXCLUDE</code> : Exclude non-associative arrays from aligning.
	 * </p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidDepth
	 * @return array <p>The aligned array with the set of given keys.</p>
	 */
	final public static function align(array $array, array $keys, ?int $depth = null, int $flags = 0x00) : array
	{
		//align
		$is_assoc = self::isAssociative($array);
		if (($is_assoc && !($flags & self::ALIGN_ASSOC_EXCLUDE)) || (!$is_assoc && !($flags & self::ALIGN_NONASSOC_EXCLUDE))) {
			$alignment = [];
			foreach ($keys as $key) {
				if (array_key_exists($key, $array)) {
					$alignment[$key] = $array[$key];
				}
			}
			$array = $alignment + $array;
			unset($alignment);
		}
		
		//depth
		if ($depth === 0) {
			return $array;
		} elseif (isset($depth) && $depth < 0) {
			throw new Exceptions\InvalidDepth(['depth' => $depth]);
		}
		$next_depth = isset($depth) ? $depth - 1 : null;
		
		//recursion
		foreach ($array as &$v) {
			if (is_array($v)) {
				$v = self::align($v, $keys, $next_depth, $flags);
			}
		}
		unset($v);
		
		//return
		return $array;
	}
	
	/**
	 * Check if a given array has a given path.
	 * 
	 * A path is recognized as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo</samp> is equivalent to <code>$array['foo']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar</samp> is equivalent to <code>$array['foo']['bar']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar.123</samp> is equivalent to <code>$array['foo']['bar'][123]</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to check in.</p>
	 * @param string $path <p>The path to check for.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @return bool <p>Boolean <code>true</code> if the given array has the given path.</p>
	 */
	final public static function has(array $array, string $path, string $delimiter = '.') : bool
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//check
		$pointer = $array;
		foreach (explode($delimiter, $path) as $key) {
			if (!is_array($pointer) || !array_key_exists($key, $pointer)) {
				return false;
			}
			$pointer = $pointer[$key];
		}
		return true;
	}
	
	/**
	 * Get value from a given array at a given path.
	 * 
	 * A path is recognized as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo</samp> is equivalent to <code>$array['foo']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar</samp> is equivalent to <code>$array['foo']['bar']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar.123</samp> is equivalent to <code>$array['foo']['bar'][123]</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to get from.</p>
	 * @param string $path <p>The path to get from.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\PathNotFound
	 * @return mixed <p>The value from the given array at the given path.</p>
	 */
	final public static function get(array $array, string $path, string $delimiter = '.')
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//get
		$pointer = $array;
		foreach (explode($delimiter, $path) as $key) {
			if (!is_array($pointer) || !isset($pointer[$key])) {
				throw new Exceptions\PathNotFound(['path' => $path]);
			}
			$pointer = $pointer[$key];
		}
		return $pointer;
	}
	
	/**
	 * Set value in a given array at a given path.
	 * 
	 * A path is recognized as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo</samp> is equivalent to <code>$array['foo']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar</samp> is equivalent to <code>$array['foo']['bar']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar.123</samp> is equivalent to <code>$array['foo']['bar'][123]</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array [reference] <p>The array to set in.</p>
	 * @param string $path <p>The path to set at.</p>
	 * @param mixed $value <p>The value to set.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\PathKeySetIntoNonArray
	 * @return void
	 */
	final public static function set(array &$array, string $path, $value, string $delimiter = '.') : void
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//set
		$pointer = &$array;
		foreach (explode($delimiter, $path) as $key) {
			if (isset($pointer) && !is_array($pointer)) {
				throw new Exceptions\PathKeySetIntoNonArray(['path' => $path, 'key' => $key, 'value' => $pointer]);
			}
			$pointer = &$pointer[$key];
		}
		$pointer = $value;
		unset($pointer);
	}
	
	/**
	 * Delete a given path from a given array.
	 * 
	 * A path is recognized as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo</samp> is equivalent to <code>$array['foo']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar</samp> is equivalent to <code>$array['foo']['bar']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar.123</samp> is equivalent to <code>$array['foo']['bar'][123]</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array [reference] <p>The array to delete from.</p>
	 * @param string $path <p>The path to delete.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\PathKeyDeleteFromNonArray
	 * @return void
	 */
	final public static function delete(array &$array, string $path, string $delimiter = '.') : void
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//crumbs
		$crumbs = [];
		$pointer = &$array;
		$keys = explode($delimiter, $path);
		foreach ($keys as $key) {
			if (isset($pointer)) {
				if (!is_array($pointer)) {
					throw new Exceptions\PathKeyDeleteFromNonArray(['path' => $path, 'key' => $key, 'value' => $pointer]);
				} elseif (!array_key_exists($key, $pointer)) {
					break;
				}
			}
			$crumbs[] = ['target' => &$pointer, 'key' => $key];
			$pointer = &$pointer[$key];
		}
		unset($pointer);
		
		//delete
		$delete = count($keys) === count($crumbs);
		foreach (array_reverse($crumbs, true) as &$crumb) {
			if ($delete || empty($crumb['target'][$crumb['key']])) {
				unset($crumb['target'][$crumb['key']]);
			}
			if (!empty($crumb['target'])) {
				break;
			}
		}
		unset($crumb, $crumbs);
	}
	
	/**
	 * Wrap a given array into a single dimensional pathed one.
	 * 
	 * The returning array is a single dimensional one of non-array values with all the nested keys set as paths.<br>
	 * Each path is set as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <code>$array['foo']</code> is converted to <samp>foo</samp>;<br>
	 * &nbsp; &#8226; &nbsp; <code>$array['foo']['bar']</code> is converted to <samp>foo.bar</samp>;<br>
	 * &nbsp; &#8226; &nbsp; <code>$array['foo']['bar'][123]</code> is converted to <samp>foo.bar.123</samp>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to wrap.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @return array <p>The wrapped array.</p>
	 */
	final public static function wrap(array $array, string $delimiter = '.') : array
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//wrap
		$wrap = [];
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach (self::wrap($v, $delimiter) as $path => $value) {
					$wrap[$k . $delimiter . $path] = $value;
				}
			} else {
				$wrap[$k] = $v;
			}
		}
		return $wrap;
	}
	
	/**
	 * Unwrap a given array into a multiple dimensional one.
	 * 
	 * The returning array is a multiple dimensional one with all the paths broken down into nested keys.<br>
	 * Each path is recognized as <samp>key1 + delimiter + key2 + delimiter + ...</samp>, like so:<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo</samp> is converted to <code>$array['foo']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar</samp> is converted to <code>$array['foo']['bar']</code>;<br>
	 * &nbsp; &#8226; &nbsp; <samp>foo.bar.123</samp> is converted to <code>$array['foo']['bar'][123]</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to unwrap.</p>
	 * @param string $delimiter [default = '.'] <p>The path delimiter character to use.<br>
	 * It must be a single ASCII character.</p>
	 * @throws \Feralygon\Kit\Core\Utilities\Data\Exceptions\InvalidPathDelimiter
	 * @return array <p>The unwrapped array.</p>
	 */
	final public static function unwrap(array $array, string $delimiter = '.') : array
	{
		//validate
		if (strlen($delimiter) !== 1) {
			throw new Exceptions\InvalidPathDelimiter(['delimiter' => $delimiter]);
		}
		
		//unwrap
		$unwrap = [];
		foreach ($array as $path => $value) {
			[$key, $path2] = explode($delimiter, $path, 2) + [1 => null];
			if (isset($path2)) {
				if (!array_key_exists($key, $unwrap) || !is_array($unwrap[$key])) {
					$unwrap[$key] = [];
				}
				$unwrap[$key][$path2] = $value;
			} else {
				$unwrap[$key] = $value;
			}
		}
		
		//recursion
		foreach ($unwrap as $k => $v) {
			if (is_array($v)) {
				$unwrap[$k] = self::unwrap($v, $delimiter);
			}
		}
		
		//return
		return $unwrap;
	}
	
	/**
	 * Coalesce value from a given array.
	 * 
	 * The returning value is the first one from the given array which is not <code>null</code>.
	 * 
	 * @since 1.0.0
	 * @param array $array <p>The array to coalesce from.</p>
	 * @param array $keys [default = []] <p>The keys to coalesce by.<br>
	 * If empty, then all the values from the given array are used to coalesce by, otherwise only the values in the matching keys are used.<br>
	 * The order of these keys also establish the order of the coalesce operation.
	 * </p>
	 * @param int|string|null $coalesced_key [reference output] [default = null] <p>The coalesced key corresponding to the returned value.</p>
	 * @return mixed <p>The coalesced value from the given array or <code>null</code> if no value is set.</p>
	 */
	final public static function coalesce(array $array, array $keys = [], &$coalesced_key = null)
	{
		$coalesced_key = null;
		foreach (empty($keys) ? array_keys($array) : $keys as $key) {
			if (isset($array[$key])) {
				$coalesced_key = $key;
				return $array[$key];
			}
		}
		return null;
	}
}
