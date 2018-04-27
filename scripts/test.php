<?php

require_once __DIR__ . '/../autoload.php';

use Feralygon\Kit\Root\System;
use Feralygon\Kit\Factories\Component as FComponent;

System::setEnvironment('development');

var_dump(System::hasCommand('0lsa'));die();


$text_options = [
	'info_scope' => 0
];


$value = 'hasd';

$input = FComponent::input('string');
$input->addModifier('values', ['values' => ['asd', 'ggg', 12345678]]);
//$input->addModifier('maximum', ['value' => 'January 3rd 2017, 8PM', 'exclusive' => 1]);
/*
$input->addModifier('range', [
	'min_value' => '2/12/04 02:54:23', 
	'max_value' => 'January 3rd 2017, 8PM',
	'min_exclusive' => 0,
	'max_exclusive' => 0
]);
*/
//$input->addModifier('filters.format', ['format' => 'H (is)']);
//$input->addModifier('filters.iso8601');



echo "INPUT:\n";
var_dump($input->getName());
var_dump($input->setValue($value, true));
var_dump($input->isInitialized() ? $input->getValue() : null);
var_dump($input->isInitialized() ? $input->getValueString($text_options) : null);
var_dump($input->getLabel($text_options));
var_dump($input->getDescription($text_options));
var_dump($input->getMessage($text_options));

$schema = $input->getSchema();
var_dump([
	'name' => $schema->name,
	'nullable' => $schema->nullable,
	'data' => $schema->data,
	'modifiers_count' => count($schema->modifiers)
]);

echo "\n\nINPUT ERROR: ";
var_dump($input->getErrorMessage($text_options));
echo "\n\n";


foreach ($input->getModifiers() as $i => $modifier) {
	echo "INPUT MODIFIER [{$i}]:\n";
	var_dump($modifier->getName());
	var_dump($modifier->getLabel($text_options));
	var_dump($modifier->getMessage($text_options));
	var_dump($modifier->getString($text_options));
	
	$schema = $modifier->getSchema();
	if (isset($schema)) {
		var_dump([
			'name' => $schema->name,
			'data' => $schema->data
		]);
	}
	echo "\n\n";
}
