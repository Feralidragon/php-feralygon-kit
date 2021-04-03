<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Dracodeum\Kit\Tests\Components\Type\Prototypes\Mutators\Stringables;

use PHPUnit\Framework\TestCase;
use Dracodeum\Kit\Components\Type\Components\Mutator as Component;
use Dracodeum\Kit\Components\Type\Prototypes\Mutators\Stringables\Base64 as Prototype;
use Dracodeum\Kit\Primitives\{
	Error,
	Text
};

/** @see \Dracodeum\Kit\Components\Type\Prototypes\Mutators\Stringables\Base64 */
class Base64Test extends TestCase
{
	//Public methods
	/**
	 * Test process.
	 * 
	 * @testdox Process
	 * @dataProvider provideProcessData
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
			['aBCd'],
			['aB/C'],
			['aB/Cd3'],
			['aB/Cd3=='],
			['aB/Cd3+'],
			['aB/Cd3+='],
			['aB_Cd3-'],
			['aBCd', ['url_safe' => false]],
			['aB/Cd3+', ['url_safe' => false]],
			['aB/Cd3+=', ['url_safe' => false]],
			['aBCd', ['url_safe' => true]],
			['aB_Cd3-', ['url_safe' => true]]
		];
	}
	
	/**
	 * Test process (error).
	 * 
	 * @testdox Process (error)
	 * @dataProvider provideProcessData_Error
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
			[''],
			[' '],
			['='],
			['a'],
			['$a#b'],
			['aB/C='],
			['aB/C=='],
			['aB/Cd'],
			['aB/Cd='],
			['aB/Cd=='],
			['aB/Cd3='],
			['aB/Cd3+=='],
			['aB_Cd3-='],
			['aB_Cd3-=='],
			['aB/Cd3-'],
			['aB_Cd3+='],
			['', ['url_safe' => false]],
			[' ', ['url_safe' => false]],
			['=', ['url_safe' => false]],
			['a', ['url_safe' => false]],
			['$a#b', ['url_safe' => false]],
			['aB/Cd3+==', ['url_safe' => false]],
			['aB_Cd3-', ['url_safe' => false]],
			['aB_Cd3-=', ['url_safe' => false]],
			['aB_Cd3-==', ['url_safe' => false]],
			['aB/Cd3-', ['url_safe' => false]],
			['aB_Cd3+=', ['url_safe' => false]],
			['', ['url_safe' => true]],
			[' ', ['url_safe' => true]],
			['=', ['url_safe' => true]],
			['a', ['url_safe' => true]],
			['$a#b', ['url_safe' => true]],
			['aB/Cd3+', ['url_safe' => true]],
			['aB/Cd3+=', ['url_safe' => true]],
			['aB/Cd3+==', ['url_safe' => true]],
			['aB_Cd3-=', ['url_safe' => true]],
			['aB_Cd3-==', ['url_safe' => true]],
			['aB/Cd3-', ['url_safe' => true]],
			['aB_Cd3+=', ['url_safe' => true]]
		];
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