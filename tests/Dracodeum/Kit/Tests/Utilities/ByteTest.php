<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Tests\Utilities;

use PHPUnit\Framework\TestCase;
use Dracodeum\Kit\Utilities\Byte as UByte;
use Dracodeum\Kit\Utilities\Byte\Exceptions;

/** @see \Dracodeum\Kit\Utilities\Byte */
class ByteTest extends TestCase
{
	//Public methods
	/**
	 * Test <code>hvalue</code> method.
	 * 
	 * @dataProvider provideHvalueMethodData
	 * @testdox Byte::hvalue($value, $options) === '$expected'
	 * 
	 * @param int $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param \Dracodeum\Kit\Utilities\Byte\Options\Hvalue|array|null $options
	 * <p>The method <var>$options</var> parameter to test with.</p>
	 * @param string $expected
	 * <p>The expected method return value.</p>
	 * @return void
	 */
	public function testHvalueMethod(int $value, $options, string $expected): void
	{
		$this->assertSame($expected, UByte::hvalue($value, $options));
	}
	
	/**
	 * Provide <code>hvalue</code> method data.
	 * 
	 * @return array
	 * <p>The provided <code>hvalue</code> method data.</p>
	 */
	public function provideHvalueMethodData(): array
	{
		return [
			[0, null, '0 B'],
			[0, ['long' => true], '0 bytes'],
			[1, null, '1 B'],
			[1, ['long' => true], '1 byte'],
			[5, null, '5 B'],
			[5, ['long' => true], '5 bytes'],
			[1000, null, '1 kB'],
			[1000, ['long' => true], '1 kilobyte'],
			[5000, null, '5 kB'],
			[5000, ['long' => true], '5 kilobytes'],
			[1000000, null, '1 MB'],
			[1000000, ['long' => true], '1 megabyte'],
			[5000000, null, '5 MB'],
			[5000000, ['long' => true], '5 megabytes'],
			[1000000000, null, '1 GB'],
			[1000000000, ['long' => true], '1 gigabyte'],
			[5000000000, null, '5 GB'],
			[5000000000, ['long' => true], '5 gigabytes'],
			[1000000000000, null, '1 TB'],
			[1000000000000, ['long' => true], '1 terabyte'],
			[5000000000000, null, '5 TB'],
			[5000000000000, ['long' => true], '5 terabytes'],
			[1000000000000000, null, '1 PB'],
			[1000000000000000, ['long' => true], '1 petabyte'],
			[5000000000000000, null, '5 PB'],
			[5000000000000000, ['long' => true], '5 petabytes'],
			[1000000000000000000, null, '1 EB'],
			[1000000000000000000, ['long' => true], '1 exabyte'],
			[5000000000000000000, null, '5 EB'],
			[5000000000000000000, ['long' => true], '5 exabytes'],
			[39714, null, '39.71 kB'],
			[39714, ['long' => true], '39.71 kilobytes'],
			[39714, ['precision' => 3], '39.714 kB'],
			[39714, ['long' => true, 'precision' => 3], '39.714 kilobytes'],
			[39714, ['precision' => 1], '39.7 kB'],
			[39714, ['long' => true, 'precision' => 1], '39.7 kilobytes'],
			[39714, ['precision' => 0], '40 kB'],
			[39714, ['long' => true, 'precision' => 0], '40 kilobytes'],
			[39714, ['min_multiple' => 'MB'], '0.04 MB'],
			[39714, ['long' => true, 'min_multiple' => 'MB'], '0.04 megabytes'],
			[39714, ['precision' => 3, 'min_multiple' => 'MB'], '0.04 MB'],
			[39714, ['long' => true, 'precision' => 3, 'min_multiple' => 'MB'], '0.04 megabytes'],
			[39714, ['precision' => 5, 'min_multiple' => 'MB'], '0.03971 MB'],
			[39714, ['long' => true, 'precision' => 5, 'min_multiple' => 'MB'], '0.03971 megabytes'],
			[39714, ['max_multiple' => 'B'], '39714 B'],
			[39714, ['long' => true, 'max_multiple' => 'B'], '39714 bytes'],
			[-39714, null, '-39.71 kB'],
			[-39714, ['long' => true], '-39.71 kilobytes'],
			[-39714, ['precision' => 0], '-40 kB'],
			[-39714, ['long' => true, 'precision' => 0], '-40 kilobytes']
		];
	}
	
	/**
	 * Test <code>mvalue</code> method.
	 * 
	 * @dataProvider provideMvalueMethodData
	 * @testdox Byte::mvalue('$value') === $expected
	 * 
	 * @param string $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int $expected
	 * <p>The expected method return value.</p>
	 * @return void
	 */
	public function testMvalueMethod(string $value, int $expected): void
	{
		foreach ([false, true] as $no_throw) {
			$this->assertSame($expected, UByte::mvalue($value, $no_throw));
		}
	}
	
	/**
	 * Provide <code>mvalue</code> method data.
	 * 
	 * @return array
	 * <p>The provided <code>mvalue</code> method data.</p>
	 */
	public function provideMvalueMethodData(): array
	{
		return [
			['0', 0],
			['0 B', 0],
			['1', 1],
			['1B', 1],
			['1 B', 1],
			['1 byte', 1],
			['5', 5],
			['5B', 5],
			['5 B', 5],
			['5 bytes', 5],
			['1k', 1000],
			['1 kB', 1000],
			['1 kilobyte', 1000],
			['5k', 5000],
			['5 kB', 5000],
			['5 kilobytes', 5000],
			['1M', 1000000],
			['1 MB', 1000000],
			['1 megabyte', 1000000],
			['5M', 5000000],
			['5 MB', 5000000],
			['5 megabytes', 5000000],
			['1G', 1000000000],
			['1 GB', 1000000000],
			['1 gigabyte', 1000000000],
			['5G', 5000000000],
			['5 GB', 5000000000],
			['5 gigabytes', 5000000000],
			['1T', 1000000000000],
			['1 TB', 1000000000000],
			['1 terabyte', 1000000000000],
			['5T', 5000000000000],
			['5 TB', 5000000000000],
			['5 terabytes', 5000000000000],
			['1P', 1000000000000000],
			['1 PB', 1000000000000000],
			['1 petabyte', 1000000000000000],
			['5P', 5000000000000000],
			['5 PB', 5000000000000000],
			['5 petabytes', 5000000000000000],
			['1E', 1000000000000000000],
			['1 EB', 1000000000000000000],
			['1 exabyte', 1000000000000000000],
			['5E', 5000000000000000000],
			['5 EB', 5000000000000000000],
			['5 exabytes', 5000000000000000000],
			['39.7 kB', 39700],
			['39.7 kilobytes', 39700],
			['39.71 MB', 39710000],
			['39.71 megabytes', 39710000],
			['39.714 GB', 39714000000],
			['39.714 gigabytes', 39714000000],
			['0.039714 MB', 39714],
			['0.039714 megabytes', 39714],
			['+39.714 kB', 39714],
			['+39.714 kilobytes', 39714],
			['-39.714 kB', -39714],
			['-39.714 kilobytes', -39714],
			['-39,714 kB', -39714],
			['-39,714 kilobytes', -39714]
		];
	}
	
	/**
	 * Test <code>mvalue</code> method expecting an <code>InvalidValue</code> exception to be thrown.
	 * 
	 * @dataProvider provideMvalueMethodData_InvalidValueException
	 * @testdox Byte::mvalue('$value') --> InvalidValue exception
	 * 
	 * @param string $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testMvalueMethod_InvalidValueException(string $value): void
	{
		$this->expectException(Exceptions\Mvalue\InvalidValue::class);
		try {
			UByte::mvalue($value);
		} catch (Exceptions\Mvalue\InvalidValue $exception) {
			$this->assertSame($value, $exception->value);
			throw $exception;
		}
	}
	
	/**
	 * Test <code>mvalue</code> method with <var>$no_throw</var> set to boolean <code>true</code>, 
	 * expecting <code>null</code> to be returned.
	 * 
	 * @dataProvider provideMvalueMethodData_InvalidValueException
	 * @testdox Byte::mvalue('$value', true) === NULL
	 * 
	 * @param string $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testMvalueMethod_NoThrowNull(string $value): void
	{
		$this->assertNull(UByte::mvalue($value, true));
	}
	
	/**
	 * Provide <code>mvalue</code> method data for an <code>InvalidValue</code> exception to be thrown.
	 * 
	 * @return array
	 * <p>The provided <code>mvalue</code> method data for an <code>InvalidValue</code> exception to be thrown.</p>
	 */
	public function provideMvalueMethodData_InvalidValueException(): array
	{
		return [
			[''],
			['.'],
			['3.1'],
			['abc'],
			['1m'],
			['1 mB'],
			['5 foobytes'],
			['--5 bytes'],
			['5_bytes'],
			['bytes 5'],
			['5.5 bytes'],
			['123.4567 kB']
		];
	}
	
	/**
	 * Test <code>evaluateSize</code> method.
	 * 
	 * @dataProvider provideSizeCoercionMethodData
	 * @testdox Byte::evaluateSize(&{$value} --> &{$expected_value}) === true
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int $expected_value
	 * <p>The expected value derived from the given <var>$value</var> parameter.</p>
	 * @return void
	 */
	public function testEvaluateSizeMethod($value, int $expected_value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertTrue(UByte::evaluateSize($v, $nullable));
			$this->assertSame($expected_value, $v);
		}
	}
	
	/**
	 * Test <code>coerceSize</code> method.
	 * 
	 * @dataProvider provideSizeCoercionMethodData
	 * @testdox Byte::coerceSize({$value}) === $expected
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int|null $expected
	 * <p>The expected method return value.</p>
	 * @return void
	 */
	public function testCoerceSizeMethod($value, ?int $expected): void
	{
		foreach ([false, true] as $nullable) {
			$this->assertSame($expected, UByte::coerceSize($value, $nullable));
		}
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method.
	 * 
	 * @dataProvider provideSizeCoercionMethodData
	 * @testdox Byte::processSizeCoercion(&{$value} --> &{$expected_value}) === true
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int $expected_value
	 * <p>The expected value derived from the given <var>$value</var> parameter.</p>
	 * @return void
	 */
	public function testProcessSizeCoercionMethod($value, int $expected_value): void
	{
		foreach ([false, true] as $nullable) {
			foreach ([false, true] as $no_throw) {
				$v = $value;
				$this->assertTrue(UByte::processSizeCoercion($v, $nullable, $no_throw));
				$this->assertSame($expected_value, $v);
			}
		}
	}
	
	/**
	 * Provide size coercion method data.
	 * 
	 * @return array
	 * <p>The provided size coercion method data.</p>
	 */
	public function provideSizeCoercionMethodData(): array
	{
		return [
			[0, 0],
			[123000 , 123000],
			[-123000 , -123000],
			[0.0 , 0],
			[123000.0 , 123000],
			[-123000.0 , -123000],
			['0' , 0],
			['123000', 123000],
			['-123000', -123000],
			['123e3' , 123000],
			['123E3' , 123000],
			['-123e3' , -123000],
			['0360170', 123000],
			['0x1e078', 123000],
			['0x1E078', 123000],
			['123k', 123000],
			['123 thousand', 123000],
			['-123k', -123000],
			['-123 thousand', -123000],
			['123 M', 123000000],
			['123 million', 123000000],
			['123 B', 123000000000],
			['123 G', 123000000000],
			['123 billion', 123000000000],
			['123kB', 123000],
			['123 kilobytes', 123000],
			['-123kB', -123000],
			['-123 kilobytes', -123000],
			['123 MB', 123000000],
			['123 megabytes', 123000000],
			['123 GB', 123000000000],
			['123 gigabytes', 123000000000]
		];
	}
	
	/**
	 * Test <code>evaluateSize</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::evaluateSize(&{NULL} --> &{NULL}, true) === true
	 * 
	 * @return void
	 */
	public function testEvaluateSizeMethod_NullValue(): void
	{
		$value = null;
		$this->assertTrue(UByte::evaluateSize($value, true));
		$this->assertNull($value);
	}
	
	/**
	 * Test <code>coerceSize</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::coerceSize({NULL}, true) === NULL
	 * 
	 * @return void
	 */
	public function testCoerceSizeMethod_NullValue(): void
	{
		$this->assertNull(UByte::coerceSize(null, true));
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::processSizeCoercion(&{NULL} --> &{NULL}, true) === true
	 * 
	 * @return void
	 */
	public function testProcessSizeCoercionMethod_NullValue(): void
	{
		foreach ([false, true] as $no_throw) {
			$value = null;
			$this->assertTrue(UByte::processSizeCoercion($value, true, $no_throw));
			$this->assertNull($value);
		}
	}
	
	/**
	 * Test <code>evaluateSize</code> method expecting boolean <code>false</code> to be returned.
	 * 
	 * @dataProvider provideSizeCoercionMethodData_SizeCoercionFailedException
	 * @testdox Byte::evaluateSize(&{$value}) === false
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testEvaluateSizeMethod_False($value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertFalse(UByte::evaluateSize($v, $nullable));
			$this->assertSame($value, $v);
		}
	}
	
	/**
	 * Test <code>coerceSize</code> method expecting a <code>SizeCoercionFailed</code> exception to be thrown.
	 * 
	 * @dataProvider provideSizeCoercionMethodData_SizeCoercionFailedException
	 * @testdox Byte::coerceSize({$value}) --> SizeCoercionFailed exception
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testCoerceSizeMethod_SizeCoercionFailedException($value): void
	{
		$this->expectException(Exceptions\SizeCoercionFailed::class);
		try {
			UByte::coerceSize($value);
		} catch (Exceptions\SizeCoercionFailed $exception) {
			$this->assertSame($value, $exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method expecting a <code>SizeCoercionFailed</code> exception to be thrown.
	 * 
	 * @dataProvider provideSizeCoercionMethodData_SizeCoercionFailedException
	 * @testdox Byte::processSizeCoercion(&{$value}) --> SizeCoercionFailed exception
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testProcessSizeCoercionMethod_SizeCoercionFailedException($value): void
	{
		$v = $value;
		$this->expectException(Exceptions\SizeCoercionFailed::class);
		try {
			UByte::processSizeCoercion($v);
		} catch (Exceptions\SizeCoercionFailed $exception) {
			$this->assertSame($value, $v);
			$this->assertSame($value, $exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method with <var>$no_throw</var> set to boolean <code>true</code>, 
	 * expecting boolean <code>false</code> to be returned.
	 * 
	 * @dataProvider provideSizeCoercionMethodData_SizeCoercionFailedException
	 * @testdox Byte::processSizeCoercion(&{$value}, false|true, true) === false
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testProcessSizeCoercionMethod_NoThrowFalse($value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertFalse(UByte::processSizeCoercion($v, $nullable, true));
			$this->assertSame($value, $v);
		}
	}
	
	/**
	 * Provide size coercion method data for a <code>SizeCoercionFailed</code> exception to be thrown.
	 * 
	 * @return array
	 * <p>The provided size coercion method data for a <code>SizeCoercionFailed</code> exception to be thrown.</p>
	 */
	public function provideSizeCoercionMethodData_SizeCoercionFailedException(): array
	{
		return [
			[false],
			[true],
			[0.123],
			[''],
			['.'],
			['3.1'],
			['abc'],
			['1m'],
			['1 mB'],
			['5 foobytes'],
			['--5 bytes'],
			['5_bytes'],
			['bytes 5'],
			['5.5 bytes'],
			['123.4567 kB'],
			[[]],
			[new \stdClass()],
			[fopen(__FILE__, 'r')]
		];
	}
	
	/**
	 * Test <code>evaluateSize</code> method with a <code>null</code> value, 
	 * expecting boolean <code>false</code> to be returned.
	 * 
	 * @testdox Byte::evaluateSize(&{NULL} --> &{NULL}) === false
	 * 
	 * @return void
	 */
	public function testEvaluateSizeMethod_NullValue_False(): void
	{
		$value = null;
		$this->assertFalse(UByte::evaluateSize($value));
		$this->assertNull($value);
	}
	
	/**
	 * Test <code>coerceSize</code> method with a <code>null</code> value, 
	 * expecting a <code>SizeCoercionFailed</code> exception to be thrown.
	 * 
	 * @testdox Byte::coerceSize({NULL}) --> SizeCoercionFailed exception
	 * 
	 * @return void
	 */
	public function testCoerceSizeMethod_NullValue_SizeCoercionFailedException(): void
	{
		$this->expectException(Exceptions\SizeCoercionFailed::class);
		try {
			UByte::coerceSize(null);
		} catch (Exceptions\SizeCoercionFailed $exception) {
			$this->assertNull($exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method with a <code>null</code> value, 
	 * expecting a <code>SizeCoercionFailed</code> exception to be thrown.
	 * 
	 * @testdox Byte::processSizeCoercion(&{NULL}) --> SizeCoercionFailed exception
	 * 
	 * @return void
	 */
	public function testProcessSizeCoercionMethod_NullValue_SizeCoercionFailedException(): void
	{
		$value = null;
		$this->expectException(Exceptions\SizeCoercionFailed::class);
		try {
			UByte::processSizeCoercion($value);
		} catch (Exceptions\SizeCoercionFailed $exception) {
			$this->assertNull($value);
			$this->assertNull($exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processSizeCoercion</code> method with a <code>null</code> value, 
	 * with <var>$no_throw</var> set to boolean <code>true</code>, expecting boolean <code>false</code> to be returned.
	 * 
	 * @testdox Byte::processSizeCoercion(&{NULL}, false, true) === false
	 * 
	 * @return void
	 */
	public function testProcessSizeCoercionMethod_NullValue_NoThrowFalse(): void
	{
		$value = null;
		$this->assertFalse(UByte::processSizeCoercion($value, false, true));
		$this->assertNull($value);
	}
	
	/**
	 * Test <code>evaluateMultiple</code> method.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData
	 * @testdox Byte::evaluateMultiple(&{$value} --> &{$expected_value}) === true
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int $expected_value
	 * <p>The expected value derived from the given <var>$value</var> parameter.</p>
	 * @return void
	 */
	public function testEvaluateMultipleMethod($value, int $expected_value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertTrue(UByte::evaluateMultiple($v, $nullable));
			$this->assertSame($expected_value, $v);
		}
	}
	
	/**
	 * Test <code>coerceMultiple</code> method.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData
	 * @testdox Byte::coerceMultiple({$value}) === $expected
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int|null $expected
	 * <p>The expected method return value.</p>
	 * @return void
	 */
	public function testCoerceMultipleMethod($value, ?int $expected): void
	{
		foreach ([false, true] as $nullable) {
			$this->assertSame($expected, UByte::coerceMultiple($value, $nullable));
		}
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData
	 * @testdox Byte::processMultipleCoercion(&{$value} --> &{$expected_value}) === true
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @param int $expected_value
	 * <p>The expected value derived from the given <var>$value</var> parameter.</p>
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod($value, int $expected_value): void
	{
		foreach ([false, true] as $nullable) {
			foreach ([false, true] as $no_throw) {
				$v = $value;
				$this->assertTrue(UByte::processMultipleCoercion($v, $nullable, $no_throw));
				$this->assertSame($expected_value, $v);
			}
		}
	}
	
	/**
	 * Provide multiple coercion method data.
	 * 
	 * @return array
	 * <p>The provided multiple coercion method data.</p>
	 */
	public function provideMultipleCoercionMethodData(): array
	{
		return [
			[1, 1],
			['1', 1],
			['', 1],
			['B', 1],
			['byte', 1],
			['bytes', 1],
			[1000, 1000],
			['1000', 1000],
			['k', 1000],
			['kB', 1000],
			['kilobyte', 1000],
			['kilobytes', 1000],
			[1000000, 1000000],
			['1000000', 1000000],
			['M', 1000000],
			['MB', 1000000],
			['megabyte', 1000000],
			['megabytes', 1000000],
			[1000000000, 1000000000],
			['1000000000', 1000000000],
			['G', 1000000000],
			['GB', 1000000000],
			['gigabyte', 1000000000],
			['gigabytes', 1000000000],
			[1000000000000, 1000000000000],
			['1000000000000', 1000000000000],
			['T', 1000000000000],
			['TB', 1000000000000],
			['terabyte', 1000000000000],
			['terabytes', 1000000000000],
			[1000000000000000, 1000000000000000],
			['1000000000000000', 1000000000000000],
			['P', 1000000000000000],
			['PB', 1000000000000000],
			['petabyte', 1000000000000000],
			['petabytes', 1000000000000000],
			[1000000000000000000, 1000000000000000000],
			['1000000000000000000', 1000000000000000000],
			['E', 1000000000000000000],
			['EB', 1000000000000000000],
			['exabyte', 1000000000000000000],
			['exabytes', 1000000000000000000]
		];
	}
	
	/**
	 * Test <code>evaluateMultiple</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::evaluateMultiple(&{NULL} --> &{NULL}, true) === true
	 * 
	 * @return void
	 */
	public function testEvaluateMultipleMethod_NullValue(): void
	{
		$value = null;
		$this->assertTrue(UByte::evaluateMultiple($value, true));
		$this->assertNull($value);
	}
	
	/**
	 * Test <code>coerceMultiple</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::coerceMultiple({NULL}, true) === NULL
	 * 
	 * @return void
	 */
	public function testCoerceMultipleMethod_NullValue(): void
	{
		$this->assertNull(UByte::coerceMultiple(null, true));
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method with a <code>null</code> value.
	 * 
	 * @testdox Byte::processMultipleCoercion(&{NULL} --> &{NULL}, true) === true
	 * 
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod_NullValue(): void
	{
		foreach ([false, true] as $no_throw) {
			$value = null;
			$this->assertTrue(UByte::processMultipleCoercion($value, true, $no_throw));
			$this->assertNull($value);
		}
	}
	
	/**
	 * Test <code>evaluateMultiple</code> method expecting boolean <code>false</code> to be returned.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData_MultipleCoercionFailedException
	 * @testdox Byte::evaluateMultiple(&{$value}) === false
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testEvaluateMultipleMethod_False($value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertFalse(UByte::evaluateMultiple($v, $nullable));
			$this->assertSame($value, $v);
		}
	}
	
	/**
	 * Test <code>coerceMultiple</code> method expecting a <code>MultipleCoercionFailed</code> exception to be thrown.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData_MultipleCoercionFailedException
	 * @testdox Byte::coerceMultiple({$value}) --> MultipleCoercionFailed exception
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testCoerceMultipleMethod_MultipleCoercionFailedException($value): void
	{
		$this->expectException(Exceptions\MultipleCoercionFailed::class);
		try {
			UByte::coerceMultiple($value);
		} catch (Exceptions\MultipleCoercionFailed $exception) {
			$this->assertSame($value, $exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method expecting a <code>MultipleCoercionFailed</code> exception to be 
	 * thrown.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData_MultipleCoercionFailedException
	 * @testdox Byte::processMultipleCoercion(&{$value}) --> MultipleCoercionFailed exception
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod_MultipleCoercionFailedException($value): void
	{
		$v = $value;
		$this->expectException(Exceptions\MultipleCoercionFailed::class);
		try {
			UByte::processMultipleCoercion($v);
		} catch (Exceptions\MultipleCoercionFailed $exception) {
			$this->assertSame($value, $v);
			$this->assertSame($value, $exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method with <var>$no_throw</var> set to boolean <code>true</code>, 
	 * expecting boolean <code>false</code> to be returned.
	 * 
	 * @dataProvider provideMultipleCoercionMethodData_MultipleCoercionFailedException
	 * @testdox Byte::processMultipleCoercion(&{$value}, false|true, true) === false
	 * 
	 * @param mixed $value
	 * <p>The method <var>$value</var> parameter to test with.</p>
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod_NoThrowFalse($value): void
	{
		foreach ([false, true] as $nullable) {
			$v = $value;
			$this->assertFalse(UByte::processMultipleCoercion($v, $nullable, true));
			$this->assertSame($value, $v);
		}
	}
	
	/**
	 * Provide multiple coercion method data for a <code>MultipleCoercionFailed</code> exception to be thrown.
	 * 
	 * @return array
	 * <p>The provided multiple coercion method data for a <code>MultipleCoercionFailed</code> exception to be 
	 * thrown.</p>
	 */
	public function provideMultipleCoercionMethodData_MultipleCoercionFailedException(): array
	{
		return [
			[false],
			[true],
			[0],
			[100],
			[-1000],
			[0.123],
			[1000.1],
			[' '],
			['.'],
			['K'],
			['Bk'],
			['kb'],
			['bit'],
			['0001'],
			['0x0001'],
			['foobyte'],
			['Kilobyte'],
			[[]],
			[new \stdClass()],
			[fopen(__FILE__, 'r')]
		];
	}
	
	/**
	 * Test <code>evaluateMultiple</code> method with a <code>null</code> value, 
	 * expecting boolean <code>false</code> to be returned.
	 * 
	 * @testdox Byte::evaluateMultiple(&{NULL} --> &{NULL}) === false
	 * 
	 * @return void
	 */
	public function testEvaluateMultipleMethod_NullValue_False(): void
	{
		$value = null;
		$this->assertFalse(UByte::evaluateMultiple($value));
		$this->assertNull($value);
	}
	
	/**
	 * Test <code>coerceMultiple</code> method with a <code>null</code> value, 
	 * expecting a <code>MultipleCoercionFailed</code> exception to be thrown.
	 * 
	 * @testdox Byte::coerceMultiple({NULL}) --> MultipleCoercionFailed exception
	 * 
	 * @return void
	 */
	public function testCoerceMultipleMethod_NullValue_MultipleCoercionFailedException(): void
	{
		$this->expectException(Exceptions\MultipleCoercionFailed::class);
		try {
			UByte::coerceMultiple(null);
		} catch (Exceptions\MultipleCoercionFailed $exception) {
			$this->assertNull($exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method with a <code>null</code> value, 
	 * expecting a <code>MultipleCoercionFailed</code> exception to be thrown.
	 * 
	 * @testdox Byte::processMultipleCoercion(&{NULL}) --> MultipleCoercionFailed exception
	 * 
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod_NullValue_MultipleCoercionFailedException(): void
	{
		$value = null;
		$this->expectException(Exceptions\MultipleCoercionFailed::class);
		try {
			UByte::processMultipleCoercion($value);
		} catch (Exceptions\MultipleCoercionFailed $exception) {
			$this->assertNull($value);
			$this->assertNull($exception->getValue());
			throw $exception;
		}
	}
	
	/**
	 * Test <code>processMultipleCoercion</code> method with a <code>null</code> value, 
	 * with <var>$no_throw</var> set to boolean <code>true</code>, expecting boolean <code>false</code> to be returned.
	 * 
	 * @testdox Byte::processMultipleCoercion(&{NULL}, false, true) === false
	 * 
	 * @return void
	 */
	public function testProcessMultipleCoercionMethod_NullValue_NoThrowFalse(): void
	{
		$value = null;
		$this->assertFalse(UByte::processMultipleCoercion($value, false, true));
		$this->assertNull($value);
	}
}
