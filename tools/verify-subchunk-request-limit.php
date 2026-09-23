<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\SubChunkRequestPacket;
use pocketmine\utils\Binary;

$count = 500000;
$payload = "\x00" . Binary::writeUnsignedVarInt($count) . str_repeat("\x00", $count * 3) . "\x00\x00\x00";

$packet = new SubChunkRequestPacket();
$packet->setProtocol(ProtocolInfo::CURRENT_PROTOCOL);
$packet->setBuffer("\x00" . $payload);

try {
	$packet->decode();
	fwrite(STDERR, "FAIL: oversized SubChunkRequest was accepted\n");
	exit(1);
} catch (PacketDecodeException $e) {
	echo "PASS: " . $e->getMessage() . "\n";
}
