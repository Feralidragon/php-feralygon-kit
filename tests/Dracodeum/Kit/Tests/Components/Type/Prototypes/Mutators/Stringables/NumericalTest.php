<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Tests\Components\Type\Prototypes\Mutators\Stringables;

use PHPUnit\Framework\TestCase;
use Dracodeum\Kit\Components\Type\Components\Mutator as Component;
use Dracodeum\Kit\Components\Type\Prototypes\Mutators\Stringables\Numerical as Prototype;
use Dracodeum\Kit\Primitives\{
	Error,
	Text
};

/** @see \Dracodeum\Kit\Components\Type\Prototypes\Mutators\Stringables\Numerical */
class NumericalTest extends TestCase
{
	//Public methods
	/**
	 * Test process.
	 * 
	 * @testdox Process
	 * @dataProvider provideProcessData
	 * @dataProvider provideProcessData_Class
	 * 
	 * @param mixed $value
	 * The value to test with.
	 * 
	 * @param array $properties
	 * The properties to test with.
	 * 
	 * @return void
	 */
	public function testProcess(mixed $value, array $properties = []): void
	{
		$v = $value;
		$this->assertNull(Component::build(Prototype::class, $properties)->process($value));
		$this->assertSame($v, $value);
	}
	
	/**
	 * Provide process data.
	 * 
	 * @return array
	 * The data.
	 */
	public function provideProcessData(): array
	{
		return [
			[''],
			['0'],
			['123'],
			['9102837465'],
			["\u{216d}\u{2169}\u{2166}", ['unicode' => true]],
			["1\u{2161}3\u{2163}5\u{2165}", ['unicode' => true]]
		];
	}
	
	/**
	 * Provide process data (class).
	 * 
	 * @return array
	 * The data.
	 */
	public function provideProcessData_Class(): array
	{
		$data = $this->provideProcessData();
		foreach ($data as &$d) {
			$d[0] = new NumericalTest_Class($d[0]);
		}
		unset($d);
		return $data;
	}
	
	/**
	 * Test process (error).
	 * 
	 * @testdox Process (error)
	 * @dataProvider provideProcessData_Error
	 * @dataProvider provideProcessData_Error_Class
	 * 
	 * @param mixed $value
	 * The value to test with.
	 * 
	 * @param array $properties
	 * The properties to test with.
	 * 
	 * @return void
	 */
	public function testProcess_Error(mixed $value, array $properties = []): void
	{
		$this->assertInstanceOf(Error::class, Component::build(Prototype::class, $properties)->process($value));
	}
	
	/**
	 * Provide process data (error).
	 * 
	 * @return array
	 * The data.
	 */
	public function provideProcessData_Error(): array
	{
		return [
			[' '],
			['_'],
			['!'],
			['VII'],
			['foo'],
			['foo123'],
			['foo 123'],
			['123 456'],
			['123_456'],
			['123.456'],
			['123,456'],
			['-9102837465'],
			["\u{216d}\u{2169}\u{2166}"],
			["1\u{2161}3\u{2163}5\u{2165}"],
			[' ', ['unicode' => true]],
			['_', ['unicode' => true]],
			['!', ['unicode' => true]],
			['VII', ['unicode' => true]],
			['foo', ['unicode' => true]],
			['foo123', ['unicode' => true]],
			["\u{216d} \u{2169} \u{2166}", ['unicode' => true]],
			["\u{216d}_\u{2169}_\u{2166}", ['unicode' => true]],
			["\u{216d}\u{2169}.\u{2166}", ['unicode' => true]],
			["\u{216d}\u{2169},\u{2166}", ['unicode' => true]],
			["-\u{216d}\u{2169}\u{2166}", ['unicode' => true]],
			["f\u{03c9}\u{03c9}b\u{03b3}r1\u{2161}3\u{2163}5\u{2165}", ['unicode' => true]]
		];
	}
	
	/**
	 * Provide process data (error, class).
	 * 
	 * @return array
	 * The data.
	 */
	public function provideProcessData_Error_Class(): array
	{
		$data = $this->provideProcessData_Error();
		foreach ($data as &$d) {
			$d[0] = new NumericalTest_Class($d[0]);
		}
		unset($d);
		return $data;
	}
	
	/**
	 * Test `ExplanationProducer` interface.
	 * 
	 * @testdox ExplanationProducer interface
	 * 
	 * @see \Dracodeum\Kit\Components\Type\Prototypes\Mutator\Interfaces\ExplanationProducer
	 * 
	 * @return void
	 */
	public function testExplanationProducerInterface(): void
	{
		$this->assertInstanceOf(Text::class, Component::build(Prototype::class)->getExplanation());
	}
}



/** Test case dummy class. */
class NumericalTest_Class
{
	public function __construct(private string $string) {}
	
	public function __toString(): string
	{
		return $this->string;
	}
}
