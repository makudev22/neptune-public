<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/pocketmine/CoreConstants.php';
require dirname(__DIR__) . '/vendor/autoload.php';

use pocketmine\network\mcpe\convert\PacketIdTranslator;
use pocketmine\network\mcpe\convert\ProtocolConvertor;
use pocketmine\network\mcpe\convert\block\RuntimeBlockMapping;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\recipe\ItemNameDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputVehicleInfo;

function verifyHighInputFlags(int $protocol) : void {
	$out = new NetworkBinaryStream();
	$out->setProtocol($protocol);
	$out->putLFloat(0);
	$out->putLFloat(0);
	$out->putVector3(new Vector3(0, 0, 0));
	$out->putLFloat(0);
	$out->putLFloat(0);
	$out->putLFloat(0);
	$out->putUnsignedVarInt(2);
	$out->putVarInt(64);
	$out->putVarInt(65);
	$out->putUnsignedVarInt(0);
	$out->putUnsignedVarInt(0);
	$out->putVarInt(0);
	$out->putVector2(new Vector2(0, 0));
	$out->putUnsignedVarLong(0);
	$out->putVector3(new Vector3(0, 0, 0));
	for ($i = 0; $i < 3; ++$i) {
		$out->putBool(false);
	}
	(new PlayerAuthInputVehicleInfo())->write($out);
	$out->putLFloat(0);
	$out->putLFloat(0);
	$out->putVector3(new Vector3(0, 0, 0));
	$out->putVector2(new Vector2(0, 0));

	$packet = new PlayerAuthInputPacket();
	$packet->setProtocol($protocol);
	$packet->setBuffer($out->getBuffer());
	(new ReflectionMethod(PlayerAuthInputPacket::class, 'decodePayload'))->invoke($packet);
	$packet->reset();
	(new ReflectionMethod(PlayerAuthInputPacket::class, 'encodePayload'))->invoke($packet);

	$copy = new PlayerAuthInputPacket();
	$copy->setProtocol($protocol);
	$copy->setBuffer($packet->getBuffer());
	(new ReflectionMethod(PlayerAuthInputPacket::class, 'decodePayload'))->invoke($copy);
	if (!$copy->hasFlag(64) || !$copy->hasFlag(65) || !$copy->feof()) {
		throw new RuntimeException("Protocol $protocol failed a high input flag round trip");
	}
}

function verifyCraftingIngredient(int $protocol) : void {
	$out = new NetworkBinaryStream();
	$out->setProtocol($protocol);
	$out->putRecipeIngredient(new RecipeIngredient(new ItemNameDescriptor("minecraft:stone", 0), 1));
	$buffer = $out->getBuffer();
	if (!str_starts_with($buffer, "\x01\x04name\x0fminecraft:stone\x00") || !str_ends_with($buffer, "\x02")) {
		throw new RuntimeException("Protocol $protocol encoded CraftingData ingredients in an invalid format");
	}

	$in = new NetworkBinaryStream($buffer);
	$in->setProtocol($protocol);
	$ingredient = $in->getRecipeIngredient();
	if ($ingredient->getCount() !== 1 || !$in->feof()) {
		throw new RuntimeException("Protocol $protocol failed a CraftingData ingredient round trip");
	}
}

foreach ([ProtocolInfo::PROTOCOL_2193] as $protocol) {
	if (!in_array($protocol, ProtocolInfo::ACCEPTED_PROTOCOLS, true)) {
		throw new RuntimeException("Protocol $protocol is not accepted");
	}

	$convertor = ProtocolConvertor::getInstance();
	foreach ([
		$convertor->getChunkProtocol($protocol),
		$convertor->getCratingProtocol($protocol),
		$convertor->getBlockPaletteProtocol($protocol),
		$convertor->getItemPaletteProtocol($protocol),
	] as $profile) {
		if ($profile !== $protocol) {
			throw new RuntimeException("Protocol $protocol selected profile $profile");
		}
	}

	if (PacketIdTranslator::getInstance()->toNetworkId($protocol, ProtocolInfo::START_GAME_PACKET) === null) {
		throw new RuntimeException("Protocol $protocol has no StartGame packet mapping");
	}

	if (strlen(RuntimeBlockMapping::getInstance($protocol)->getEncodeBedrockKnownStates()) < 100000) {
		throw new RuntimeException("Protocol $protocol has an incomplete block palette");
	}

	foreach (['required_item_list.json', 'r16_to_current_item_map.json', 'item_id_map.json'] as $file) {
		$path = dirname(__DIR__) . "/src/pocketmine/resources/vanilla/items/$protocol/$file";
		$data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
		if ($data === []) {
			throw new RuntimeException("Protocol $protocol has an empty item resource: $file");
		}
	}

	$out = new NetworkBinaryStream();
	$out->setProtocol($protocol);
	$out->putNetworkItemStackDescriptor(new ItemStackWrapper(7, new ItemStack(37, 0, 1, 0, null, [], [], null)));

	$in = new NetworkBinaryStream($out->getBuffer());
	$in->setProtocol($protocol);
	$item = $in->getNetworkItemStackDescriptor();
	if ($item->getStackId() !== 7 || $item->getItemStack()->getId() !== 37 || !$in->feof()) {
		throw new RuntimeException("Protocol $protocol failed an item descriptor round trip");
	}

	verifyHighInputFlags($protocol);
	verifyCraftingIngredient($protocol);

	echo "$protocol ok\n";
}
