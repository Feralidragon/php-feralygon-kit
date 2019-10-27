<?php

/**
 * @author Cláudio "Feralidragon" Luís <claudio.luis@aptoide.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Feralygon\Kit\Prototypes\Inputs\Hash\Filters;

use Feralygon\Kit\Components\Input\Prototypes\Modifiers\Filter;
use Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\{
	Subtype as ISubtype,
	SchemaData as ISchemaData
};
use Feralygon\Kit\Traits\LazyProperties\Property;
use Feralygon\Kit\Utilities\Hash as UHash;

/**
 * This filter prototype converts a hash into a colon-hexadecimal string.
 * 
 * @property-write bool $hextets [writeonce] [transient] [coercive] [default = false]
 * <p>Colonify a given hash into hextets.</p>
 */
class Colonify extends Filter implements ISubtype, ISchemaData
{
	//Protected properties
	/** @var bool */
	protected $hextets = false;
	
	
	
	//Implemented public methods
	/** {@inheritdoc} */
	public function getName(): string
	{
		return 'colonify';
	}
	
	/** {@inheritdoc} */
	public function processValue(&$value): bool
	{
		//check
		if (!is_string($value)) {
			return false;
		}
		
		//colonify
		try {
			$value = UHash::colonify($value, $this->hextets);
		} catch (\Exception $exception) {
			return false;
		}
		return true;
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\Subtype)
	/** {@inheritdoc} */
	public function getSubtype(): string
	{
		return 'hash';
	}
	
	
	
	//Implemented public methods (Feralygon\Kit\Components\Input\Prototypes\Modifier\Interfaces\SchemaData)
	/** {@inheritdoc} */
	public function getSchemaData()
	{
		return [
			'hextets' => $this->hextets
		];
	}
	
	
	
	//Implemented protected methods (Feralygon\Kit\Prototype\Traits\PropertyBuilder)
	/** {@inheritdoc} */
	protected function buildProperty(string $name): ?Property
	{
		switch ($name) {
			case 'hextets':
				return $this->createProperty()->setMode('w--')->setAsBoolean()->bind(self::class);
		}
		return null;
	}
}
