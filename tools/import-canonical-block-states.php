<?php

declare(strict_types=1);

use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;

require dirname(__DIR__) . '/vendor/autoload.php';

[$script, $input, $output, $networkIdsOutput] = $argv + [null, null, null, null];
if ($input === null || $output === null || $networkIdsOutput === null) {
	throw new InvalidArgumentException('Usage: php tools/import-canonical-block-states.php <block_palette.nbt.gz> <canonical_block_states.nbt> <block_network_ids.json>');
}

$compressed = file_get_contents($input);
if ($compressed === false) {
	throw new RuntimeException('Unable to read input palette');
}
$raw = gzdecode($compressed);
if ($raw === false) {
	throw new RuntimeException('Unable to decompress input palette');
}
$root = (new BigEndianNBTStream())->read($raw);
$blocks = $root->getListTag('blocks');
if ($blocks === null) {
	throw new RuntimeException('Input palette does not contain blocks');
}

$stream = new NetworkLittleEndianNBTStream();
$outputData = '';
$networkIds = [];
foreach ($blocks->getValue() as $block) {
	$networkIds[] = $block->getInt('network_id');
	$block->removeTag('block_id');
	$block->removeTag('name_hash');
	$block->removeTag('network_id');
	$outputData .= $stream->write($block);
}

if (file_put_contents($output, $outputData) === false) {
	throw new RuntimeException('Unable to write output palette');
}
if (file_put_contents($networkIdsOutput, json_encode($networkIds, JSON_THROW_ON_ERROR)) === false) {
	throw new RuntimeException('Unable to write network ID map');
}
