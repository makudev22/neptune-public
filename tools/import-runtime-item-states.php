<?php

declare(strict_types=1);

[$script, $input, $output] = $argv + [null, null, null];
if ($input === null || $output === null) {
	throw new InvalidArgumentException('Usage: php tools/import-runtime-item-states.php <runtime_item_states.json> <required_item_list.json>');
}

$contents = file_get_contents($input);
if ($contents === false) {
	throw new RuntimeException('Unable to read runtime item states');
}
$states = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
$items = [];
foreach ($states as $state) {
	if (!is_array($state) || !is_string($state['name'] ?? null) || !is_int($state['id'] ?? null) || !is_int($state['version'] ?? null) || !is_bool($state['componentBased'] ?? null)) {
		throw new RuntimeException('Invalid runtime item state');
	}
	$items[$state['name']] = [
		'runtime_id' => $state['id'],
		'component_based' => $state['componentBased'],
		'version' => $state['version'],
	];
}

if (file_put_contents($output, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n") === false) {
	throw new RuntimeException('Unable to write required item list');
}
