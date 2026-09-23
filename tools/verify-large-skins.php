<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use pocketmine\entity\Skin;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlayerSkinPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\skin\SerializedSkin;
use pocketmine\network\mcpe\protocol\types\skin\SkinImage;
use pocketmine\utils\Color;
use pocketmine\utils\UUID;

foreach ([128, 256] as $height) {
	$data = str_repeat("\xff\x80\x40\xff", 256 * $height);
	$serialized = new SerializedSkin(
		"test_256x$height",
		"",
		new SkinImage($height, 256, $data),
		"",
		SkinImage::empty(),
		'{"geometry":{"default":"geometry.humanoid.custom"}}',
		"",
		"",
		"",
		[],
		false,
		false,
		false,
		null,
		"wide",
		new Color(0, 0, 0),
		SplFixedArray::fromArray([]),
		SplFixedArray::fromArray([])
	);
	$skin = $serialized->toSkin();
	if ($skin->getSkinId() !== "test_256x$height" || $skin->getSkinData() !== $data || !$skin->isValid()) {
		throw new RuntimeException("256x$height skin was replaced or rejected");
	}
	if ($skin->getClientFriendlySkinData(ProtocolInfo::PROTOCOL_2193) !== $data) {
		throw new RuntimeException("256x$height skin changed for the current protocol");
	}
	$legacy128 = $skin->getClientFriendlySkinData(ProtocolInfo::PROTOCOL_407);
	if (strlen($legacy128) !== 128 * 128 * 4 || ord($legacy128[3]) < 254) {
		throw new RuntimeException("256x$height skin was not resized for protocol 407");
	}
	$legacy64 = $skin->getClientFriendlySkinData(ProtocolInfo::PROTOCOL_113);
	if (strlen($legacy64) !== 64 * 64 * 4 || ord($legacy64[3]) < 254) {
		throw new RuntimeException("256x$height skin was not resized for protocol 113");
	}
	$stream = new NetworkBinaryStream();
	$stream->setProtocol(ProtocolInfo::PROTOCOL_407);
	$stream->putSkin($skin);
	$stream->setOffset(0);
	$stream->getString();
	$stream->getString();
	$width = $stream->getLInt();
	$wireHeight = $stream->getLInt();
	if ($width !== 128 || $wireHeight !== 128 || strlen($stream->getString()) !== 128 * 128 * 4) {
		throw new RuntimeException("256x$height skin was not resized on protocol 407 wire");
	}
	$stream = new NetworkBinaryStream();
	$stream->setProtocol(ProtocolInfo::PROTOCOL_2193);
	$stream->putSkin($skin);
	$stream->setOffset(0);
	$stream->getString();
	$stream->getString();
	$stream->getString();
	$width = $stream->getLInt();
	$wireHeight = $stream->getLInt();
	if ($width !== 256 || $wireHeight !== $height || $stream->getString() !== $data) {
		throw new RuntimeException("256x$height skin changed on current protocol wire");
	}
	echo "PASS: 256x$height skin\n";
}

$fallback = static function (string $id, string $armSize) : SerializedSkin {
	return new SerializedSkin(
		$id,
		"",
		SkinImage::empty(),
		"",
		SkinImage::empty(),
		'{"geometry":{"default":"geometry.humanoid.custom"}}',
		"",
		"",
		"",
		[],
		false,
		true,
		false,
		null,
		$armSize,
		new Color(0, 0, 0),
		SplFixedArray::fromArray([]),
		SplFixedArray::fromArray([])
	);
};

$first = $fallback("first", "wide")->toSkin();
$second = $fallback("second", "wide")->toSkin();
$slim = $fallback("slim", "slim")->toSkin();
if ($first === $second || $first->getSerializedSkin()->getSkinId() !== "first" || $second->getSerializedSkin()->getSkinId() !== "second" || $first->getSkinId() !== "Standard_Steve" || $slim->getSkinId() !== "Standard_Alex") {
	throw new RuntimeException("Default skins were shared or selected incorrectly");
}
echo "PASS: independent standard skin fallbacks\n";

foreach ([0, 128, 255] as $alpha) {
	$skin = new Skin("alpha_$alpha", str_repeat("\xff\x80\x40" . chr($alpha), 256 * 128));
	$resized = $skin->getClientFriendlySkinData(ProtocolInfo::PROTOCOL_407);
	if (abs(ord($resized[3]) - $alpha) > 2) {
		throw new RuntimeException("Alpha $alpha changed to " . ord($resized[3]));
	}
}
echo "PASS: resized skin alpha\n";

$customData = str_repeat("\x31\x72\xa4\xff", 64 * 64);
$custom = new SerializedSkin(
	"custom_skin",
	"",
	new SkinImage(64, 64, $customData),
	"",
	SkinImage::empty(),
	'{"geometry":{"default":"geometry.humanoid.custom"}}',
	"",
	"",
	"",
	[],
	false,
	false,
	false,
	"",
	"wide",
	new Color(0, 0, 0),
	SplFixedArray::fromArray([]),
	SplFixedArray::fromArray([])
);
if ($custom->getFullSkinId() === "" || $custom->toSkin()->getSkinData() !== $customData) {
	throw new RuntimeException("Custom skin data or full skin ID was lost");
}
foreach ([ProtocolInfo::PROTOCOL_407, ProtocolInfo::PROTOCOL_2193] as $protocol) {
	$stream = new NetworkBinaryStream();
	$stream->setProtocol($protocol);
	$stream->putSkin($custom->toSkin());
	$stream->setOffset(0);
	$decoded = $stream->getSkin();
	if ($decoded->getSkinData() !== $customData || $decoded->getSerializedSkin()->getFullSkinId() !== $custom->getFullSkinId()) {
		throw new RuntimeException("Custom skin changed on protocol $protocol wire");
	}
}
echo "PASS: custom skin survives modern protocol round trips\n";

$uuid = UUID::fromString("593c4047-2490-4877-8695-1c967bd6dce6");
foreach ([ProtocolInfo::PROTOCOL_407, ProtocolInfo::PROTOCOL_2193] as $protocol) {
	$skinPacket = new PlayerSkinPacket();
	$skinPacket->setProtocol($protocol);
	$skinPacket->uuid = $uuid;
	$skinPacket->skin = $custom->toSkin();
	$skinPacket->encode();
	$decodedSkinPacket = new PlayerSkinPacket($skinPacket->getBuffer());
	$decodedSkinPacket->setProtocol($protocol);
	$decodedSkinPacket->decode();
	if ($decodedSkinPacket->skin->getSkinData() !== $customData) {
		throw new RuntimeException("Skin change packet lost custom pixels on protocol $protocol");
	}

	$listPacket = new PlayerListPacket();
	$listPacket->setProtocol($protocol);
	$listPacket->type = PlayerListPacket::TYPE_ADD;
	$listPacket->entries[] = PlayerListEntry::createAdditionEntry($uuid, 1, "skin_test", $custom->toSkin());
	$listPacket->encode();
	$decodedListPacket = new PlayerListPacket($listPacket->getBuffer());
	$decodedListPacket->setProtocol($protocol);
	$decodedListPacket->decode();
	if ($decodedListPacket->entries[0]->skin->getSkinData() !== $customData) {
		throw new RuntimeException("Player list packet lost custom pixels on protocol $protocol");
	}
}
echo "PASS: custom skins survive player packets\n";
