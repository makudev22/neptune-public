<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe;

use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\nbt\LittleEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\network\mcpe\convert\EntityMetadataTranslator;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\command\OriginDataType;
use pocketmine\network\mcpe\protocol\types\entity\AttributeModifier;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\types\GameRuleType;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\recipe\ComplexAliasItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\IntIdMetaItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\ItemNameDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\ItemDescriptorType;
use pocketmine\network\mcpe\protocol\types\recipe\MolangItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;
use pocketmine\network\mcpe\protocol\types\recipe\StringIdMetaItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\TagItemDescriptor;
use pocketmine\network\mcpe\protocol\types\skin\PersonaPieceTintColor;
use pocketmine\network\mcpe\protocol\types\skin\PersonaSkinPiece;
use pocketmine\network\mcpe\protocol\types\skin\SerializedSkin;
use pocketmine\network\mcpe\protocol\types\skin\SkinAnimation;
use pocketmine\network\mcpe\protocol\types\skin\SkinImage;
use pocketmine\network\mcpe\protocol\types\StructureEditorData;
use pocketmine\network\mcpe\protocol\types\StructureSettings;
use pocketmine\utils\BinaryDataException;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Color;
use pocketmine\utils\UUID;
use SplFixedArray;
use UnexpectedValueException;

use function assert;
use function chr;
use function count;
use function ord;
use function strlen;

class NetworkBinaryStream extends BinaryStream
{

	/** @var int[] */
	public static array $shieldItemRuntimeIds = [];

	protected int $protocol = ProtocolInfo::CURRENT_PROTOCOL;

	public function setProtocol(int $protocol) : void{
		$this->protocol = $protocol;
	}

	public function getProtocol() : int{
		return $this->protocol;
	}

	public function getString() : string
	{
		return $this->get($this->getUnsignedVarInt());
	}

	public function putString(string $v) : void
	{
		$this->putUnsignedVarInt(strlen($v));
		$this->put($v);
	}

	public function getUUID() : UUID
	{
		//This is actually two little-endian longs: UUID Most followed by UUID Least
		$part1 = $this->getLInt();
		$part0 = $this->getLInt();
		$part3 = $this->getLInt();
		$part2 = $this->getLInt();

		return new UUID($part0, $part1, $part2, $part3);
	}

	public function putUUID(UUID $uuid) : void
	{
		$this->putLInt($uuid->getPart(1));
		$this->putLInt($uuid->getPart(0));
		$this->putLInt($uuid->getPart(3));
		$this->putLInt($uuid->getPart(2));
	}

	public function getSkin() : Skin{
		$skinId = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_428) {
			$skinPlayFabId = $this->getString();
		}
		$skinResourcePatch = $this->getString();
		$skinData = $this->getSkinImage();
		$animationCount = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getLInt();
		if ($animationCount > 128) {
			throw new UnexpectedValueException("Too many skin animations: $animationCount");
		}
		$animations = [];
		for ($i = 0; $i < $animationCount; ++$i) {
			$animations[] = new SkinAnimation(
				$this->getSkinImage(),
				$this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getLInt(),
				$this->getLFloat(),
				$this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : ($this->protocol >= ProtocolInfo::PROTOCOL_419 ? $this->getLInt() : 0)
			);
		}
		$capeData = $this->getSkinImage();
		$geometryData = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$geometryDataVersion = $this->getString();
		}
		$animationData = $this->getString();
		if ($this->protocol < ProtocolInfo::PROTOCOL_465) {
			$premium = $this->getBool();
			$persona = $this->getBool();
			$capeOnClassic = $this->getBool();
		}
		$capeId = $this->getString();
		$fullSkinId = $this->getString();

		$armSize = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? ($this->getByte() === 0 ? "slim" : "wide") : $this->getString();
		$skinColor = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? Color::fromARGB($this->getLInt()) : Color::fromHexString($this->getString());
		$personaPieceCount = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getLInt();
		if ($personaPieceCount > 128) {
			throw new UnexpectedValueException("Too many persona pieces: $personaPieceCount");
		}
		$personaPieces = [];
		for ($i = 0; $i < $personaPieceCount; ++$i) {
			$personaPieces[] = new PersonaSkinPiece(
				$this->getString(),
				$this->protocol >= ProtocolInfo::PROTOCOL_2193 ? self::personaPieceTypeFromWire($this->getLInt()) : $this->getString(),
				$this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUUID()->toString() : $this->getString(),
				$this->getBool(),
				$this->getString()
			);
		}
		$pieceTintColorCount = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getLInt();
		if ($pieceTintColorCount > 128) {
			throw new UnexpectedValueException("Too many piece tint colors: $pieceTintColorCount");
		}
		$pieceTintColors = [];
		for ($i = 0; $i < $pieceTintColorCount; ++$i) {
			$pieceType = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? self::personaPieceTintTypeFromWire($this->getString()) : $this->getString();
			$colorCount = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? 4 : $this->getLInt();
			$colors = [];
			for ($j = 0; $j < $colorCount; ++$j) {
				$colors[] = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getLInt() : $this->getString();
			}
			$pieceTintColors[] = new PersonaPieceTintColor(
				$pieceType,
				$colors
			);
		}
		$personaPieces = SplFixedArray::fromArray($personaPieces);
		$pieceTintColors = SplFixedArray::fromArray($pieceTintColors);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$premium = $this->getBool();
			$persona = $this->getBool();
			$capeOnClassic = $this->getBool();
			$isPrimaryUser = $this->getBool();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_568) {
				$override = $this->getBool();
			}
		}
		$trustedSkinFlag = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getString() : "true";
		$profileHash = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getString() : "";

		return (new SerializedSkin($skinId, $skinPlayFabId ?? "", $skinData, $capeId, $capeData, $skinResourcePatch, $geometryData, $geometryDataVersion ?? "", $animationData, $animations, $premium ?? false, $persona ?? false, $capeOnClassic ?? false, $fullSkinId, $armSize, $skinColor, $personaPieces, $pieceTintColors, $isPrimaryUser ?? true, $override ?? true, $trustedSkinFlag, $profileHash))->toSkin();
	}

	public function putSkin(Skin $skin) : void{
		$skin = $skin->getSerializedSkin();
		$this->putString($skin->getSkinId());
		if ($this->protocol >= ProtocolInfo::PROTOCOL_428) {
			$this->putString($skin->getPlayFabId());
		}
		$this->putString($skin->getResourcePatch());
		$this->putSkinImage($skin->getSkinImage());
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putUnsignedVarInt(count($skin->getAnimationFrames()));
		} else {
			$this->putLInt(count($skin->getAnimationFrames()));
		}
		foreach ($skin->getAnimationFrames() as $animation) {
			$this->putSkinImage($animation->getImage());
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt($animation->getType());
			} else {
				$this->putLInt($animation->getType());
			}
			$this->putLFloat($animation->getFrames());
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt($animation->getExpressionType());
			} elseif ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
				$this->putLInt($animation->getExpressionType());
			}
		}
		$this->putSkinImage($skin->getCapeImage());
		$this->putString($skin->getGeometryData());
		if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
			$this->putString($skin->getGeometryDataEngineVersion());
		}
		$this->putString($skin->getAnimationData());
		if ($this->protocol < ProtocolInfo::PROTOCOL_465) {
			$this->putBool($skin->isPremiumSkin());
			$this->putBool($skin->isPersonaSkin());
			$this->putBool($skin->isCapeOnClassicSkin());
		}
		$this->putString($skin->getCapeId());
		$this->putString($skin->getFullSkinId());
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putByte($skin->getArmSize() === "slim" ? 0 : 1);
				$this->putLInt($skin->getSkinColor()->toARGB());
				$this->putUnsignedVarInt(count($skin->getPersonaPieces()));
			} else {
				$this->putString($skin->getArmSize());
				$this->putString($skin->getSkinColor()->toHexString());
				$this->putLInt(count($skin->getPersonaPieces()));
			}
			foreach ($skin->getPersonaPieces() as $piece) {
				$this->putString($piece->getPieceId());
				if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
					$this->putLInt(self::personaPieceTypeToWire($piece->getPieceType()));
					$this->putUUID(self::personaPackIdToUuid($piece->getPackId()));
				} else {
					$this->putString($piece->getPieceType());
					$this->putString($piece->getPackId());
				}
				$this->putBool($piece->isDefaultPiece());
				$this->putString($piece->getProductId());
			}
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt(count($skin->getPieceTintColors()));
			} else {
				$this->putLInt(count($skin->getPieceTintColors()));
			}
			foreach ($skin->getPieceTintColors() as $tint) {
				$this->putString($this->protocol >= ProtocolInfo::PROTOCOL_2193 ? self::personaPieceTintTypeToWire($tint->getPieceType()) : $tint->getPieceType());
				if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
					$this->putLInt(count($tint->getColors()));
				}
				foreach ($tint->getColors() as $color) {
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
						$this->putLInt((int) $color);
					} else {
						$this->putString($color);
					}
				}
			}
			if ($this->protocol >= ProtocolInfo::PROTOCOL_465) {
				$this->putBool($skin->isPremiumSkin());
				$this->putBool($skin->isPersonaSkin());
				$this->putBool($skin->isCapeOnClassicSkin());
				$this->putBool($skin->isPrimaryUser());
			if ($this->protocol >= ProtocolInfo::PROTOCOL_568) {
				$this->putBool($skin->isOverride());
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putString($skin->getTrustedSkinFlag());
			$this->putString($skin->getProfileHash());
		}
		}
	}

	private static function personaPieceTypeToWire(string $type) : int
	{
		return [
			"persona_skeleton" => 1,
			"persona_body" => 2,
			"persona_skin" => 3,
			"persona_bottom" => 4,
			"persona_feet" => 5,
			"persona_dress" => 6,
			"persona_top" => 7,
			"persona_high_pants" => 8,
			"persona_hand" => 9,
			"persona_hands" => 9,
			"persona_outerwear" => 10,
			"persona_facial_hair" => 11,
			"persona_mouth" => 12,
			"persona_eyes" => 13,
			"persona_hair" => 14,
			"persona_hood" => 15,
			"persona_back" => 16,
			"persona_face_accessory" => 17,
			"persona_head" => 18,
			"persona_legs" => 19,
			"persona_left_leg" => 20,
			"persona_right_leg" => 21,
			"persona_arms" => 22,
			"persona_left_arm" => 23,
			"persona_right_arm" => 24,
			"persona_capes" => 25,
			"persona_classic_skin" => 26,
			"persona_emote" => 27,
			"persona_unsupported" => 28,
			"unsupported" => 28,
		][$type] ?? 0;
	}

	private static function personaPieceTypeFromWire(int $type) : string
	{
		return [
			1 => "persona_skeleton",
			2 => "persona_body",
			3 => "persona_skin",
			4 => "persona_bottom",
			5 => "persona_feet",
			6 => "persona_dress",
			7 => "persona_top",
			8 => "persona_high_pants",
			9 => "persona_hand",
			10 => "persona_outerwear",
			11 => "persona_facial_hair",
			12 => "persona_mouth",
			13 => "persona_eyes",
			14 => "persona_hair",
			15 => "persona_hood",
			16 => "persona_back",
			17 => "persona_face_accessory",
			18 => "persona_head",
			19 => "persona_legs",
			20 => "persona_left_leg",
			21 => "persona_right_leg",
			22 => "persona_arms",
			23 => "persona_left_arm",
			24 => "persona_right_arm",
			25 => "persona_capes",
			26 => "persona_classic_skin",
			27 => "persona_emote",
			28 => "unsupported",
		][$type] ?? "unsupported";
	}

	private static function personaPieceTintTypeToWire(string $type) : string
	{
		if ($type === "persona_hand") {
			return "hands";
		}

		return str_starts_with($type, "persona_") ? substr($type, 8) : $type;
	}

	private static function personaPieceTintTypeFromWire(string $type) : string
	{
		if ($type === "hands") {
			return "persona_hand";
		}

		return $type === "unsupported" ? $type : "persona_" . $type;
	}

	private static function personaPackIdToUuid(string $packId) : UUID
	{
		try {
			return UUID::fromString($packId);
		} catch (\InvalidArgumentException) {
			return UUID::fromString("00000000-0000-0000-0000-000000000000");
		}
	}

	private function getSkinImage() : SkinImage
	{
		$width = $this->getLInt();
		$height = $this->getLInt();
		$data = $this->getString();
		return new SkinImage($height, $width, $data);
	}

	private function putSkinImage(SkinImage $image) : void
	{
		$this->putLInt($image->getWidth());
		$this->putLInt($image->getHeight());
		$this->putString($image->getData());
	}

	/**
	 * @return int[]
	 * @phpstan-return array{0: int, 1: int, 2: int}
	 * @throws BinaryDataException
	 */
	private function getItemStackHeader(bool $network = true) : array{
		$id = $network && $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getSignedLShort() : $this->getVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193 || $id !== 0 && $this->protocol >= ProtocolInfo::PROTOCOL_431) {
			$count = $this->getLShort();
			$meta = $this->getUnsignedVarInt();
		} elseif ($id === 0) {
			return [0, 0, 0];
		} else {
			$auxValue = $this->getVarInt();
			$meta = $auxValue >> 8;
			if ($this->protocol < ProtocolInfo::PROTOCOL_407 && $meta === 0x7fff) {
				$meta = -1;
			}
			$count = $auxValue & 0xff;
		}

		return [$id, $count, $meta];
	}

	private function putItemStackHeader(ItemStack $itemStack, bool $network = true) : bool{
		if($itemStack->getId() === 0){
			if ($network && $this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putLShort(0);
				$this->putLShort(0);
				$this->putUnsignedVarInt(0);
				return true;
			}
			if (!$network && $this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putVarInt(0);
				$this->putLShort(0);
				$this->putUnsignedVarInt(0);
				return true;
			}
			$this->putVarInt(0);
			return false;
		}

		if ($network && $this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putLShort($itemStack->getId());
		} else {
			$this->putVarInt($itemStack->getId());
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			$this->putLShort($itemStack->getCount());
			$this->putUnsignedVarInt($itemStack->getMeta());
		} else {
			$auxValue = (($itemStack->getMeta() & 0x7fff) << 8) | $itemStack->getCount();
			$this->putVarInt($auxValue);
		}

		return true;
	}

	private function getItemStackFooter(int $id, int $meta, int $count, bool $network = true) : ItemStack{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			$blockRuntimeId = $network && $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getVarInt();
			$binaryExtraData = new NetworkBinaryStream($this->getString());
		} else {
			$binaryExtraData = $this;
			$blockRuntimeId = 0;
		}

		[$compound, $canPlaceOn, $canDestroy, $shieldBlockingTick] = $this->getItemStackExtraData($id, $binaryExtraData);
		return new ItemStack($id, $meta, $count, $blockRuntimeId, $compound, $canPlaceOn, $canDestroy, $shieldBlockingTick);
	}

	private function putItemStackFooter(ItemStack $itemStack, bool $network = true) : void{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			if ($network && $this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt($itemStack->getBlockRuntimeId());
			} else {
				$this->putVarInt($itemStack->getBlockRuntimeId());
			}
			$extraData = new NetworkBinaryStream();
			$extraData->setProtocol($this->protocol);
			$this->putItemStackExtraData($itemStack, $extraData);
			$this->putString($extraData->getBuffer());
		} else {
			$this->putItemStackExtraData($itemStack, $this);
		}
	}

	public function getItemStackExtraData(int $id, NetworkBinaryStream $extraData) : array{
		$nbtLen = $extraData->getLShort();

		/** @var CompoundTag|null $compound */
		$compound = null;
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($nbtLen === 0xffff) {
				$nbtDataVersion = $extraData->getByte();
				if ($nbtDataVersion !== 1) {
					throw new PacketDecodeException("Unexpected NBT data version $nbtDataVersion");
				}

				if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
					$decodedNBT = (new LittleEndianNBTStream())->read($extraData->buffer, false, $extraData->offset, 512);
				} else {
					$decodedNBT = (new NetworkLittleEndianNBTStream())->read($extraData->buffer, false, $extraData->offset, 512);
				}

				if (!($decodedNBT instanceof CompoundTag)) {
					throw new PacketDecodeException("Unexpected root tag type for itemstack");
				}

				$compound = $decodedNBT;
			} elseif ($nbtLen !== 0) {
				throw new PacketDecodeException("Unexpected fake NBT length $nbtLen");
			}
		} elseif ($nbtLen > 0) {
			$decodedNBT = (new LittleEndianNBTStream())->read($extraData->get($nbtLen));
			if (!($decodedNBT instanceof CompoundTag)) {
				throw new PacketDecodeException("Unexpected root tag type for itemstack");
			}

			$compound = $decodedNBT;
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			$canPlaceOn = [];
			for ($i = 0, $canPlaceOnCount = $extraData->getLInt(); $i < $canPlaceOnCount; ++$i) {
				$canPlaceOn[] = $extraData->get($extraData->getLShort());
			}

			$canDestroy = [];
			for ($i = 0, $canDestroyCount = $extraData->getLInt(); $i < $canDestroyCount; ++$i) {
				$canDestroy[] = $extraData->get($extraData->getLShort());
			}
		} else {
			$canPlaceOn = [];
			for ($i = 0, $canPlaceOnCount = $extraData->getVarInt(); $i < $canPlaceOnCount; ++$i) {
				$canPlaceOn[] = $extraData->getString();
			}

			$canDestroy = [];
			for ($i = 0, $canDestroyCount = $extraData->getVarInt(); $i < $canDestroyCount; ++$i) {
				$canDestroy[] = $extraData->getString();
			}
		}

		$shieldBlockingTick = null;
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if (!isset(self::$shieldItemRuntimeIds[$this->protocol])) {
				if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
					self::$shieldItemRuntimeIds[$this->protocol] = ItemTranslator::getInstance($this->protocol)->toNetworkId(ItemIds::SHIELD, 0)[0];
				} else {
					self::$shieldItemRuntimeIds[$this->protocol] = ItemIds::SHIELD;
				}
			}

			if ($id === self::$shieldItemRuntimeIds[$this->protocol]) {
				if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
					$shieldBlockingTick = $extraData->getLLong();
				} else {
					$shieldBlockingTick = $extraData->getVarLong();
				}
			}
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			if(!$extraData->feof()){
				throw new PacketDecodeException("Unexpected trailing extradata for network item $id");
			}
		}

		return [$compound, $canPlaceOn, $canDestroy, $shieldBlockingTick];
	}

	public function putItemStackExtraData(ItemStack $itemStack, NetworkBinaryStream $extraData) : void{
		$nbt = $itemStack->getNbt();

		if ($nbt !== null) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
				$extraData->putLShort(0xffff);
				$extraData->putByte(1); //TODO: some kind of count field? always 1 as of 1.9.0
				if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
					$extraData->put((new LittleEndianNBTStream())->write($nbt));
				} else {
					$extraData->put((new NetworkLittleEndianNBTStream())->write($nbt));
				}
			} else {
				$nbt = (new LittleEndianNBTStream())->write($nbt);
				$extraData->putLShort(strlen($nbt));
				$extraData->put($nbt);
			}
		} else {
			$extraData->putLShort(0);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
			$extraData->putLInt(count($itemStack->getCanPlaceOn()));
			foreach ($itemStack->getCanPlaceOn() as $entry) {
				$extraData->putLShort(strlen($entry));
				$extraData->put($entry);
			}
			$extraData->putLInt(count($itemStack->getCanDestroy()));
			foreach ($itemStack->getCanDestroy() as $entry) {
				$extraData->putLShort(strlen($entry));
				$extraData->put($entry);
			}
		} else {
			$extraData->putVarInt(count($itemStack->getCanPlaceOn()));
			foreach ($itemStack->getCanPlaceOn() as $entry) {
				$extraData->putString($entry);
			}
			$extraData->putVarInt(count($itemStack->getCanDestroy()));
			foreach ($itemStack->getCanDestroy() as $entry) {
				$extraData->putString($entry);
			}
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if (!isset(self::$shieldItemRuntimeIds[$this->protocol])) {
				if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
					self::$shieldItemRuntimeIds[$this->protocol] = ItemTranslator::getInstance($this->protocol)->toNetworkId(ItemIds::SHIELD, 0)[0];
				} else {
					self::$shieldItemRuntimeIds[$this->protocol] = ItemIds::SHIELD;
				}
			}

			if ($itemStack->getId() === self::$shieldItemRuntimeIds[$this->protocol]) {
				$blockingTick = $itemStack->getShieldBlockingTick() ?? 0; //"blocking tick" (ffs mojang)
				if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
					$extraData->putLLong($blockingTick);
				} else {
					$extraData->putVarLong($blockingTick);
				}
			}
		}
	}

	/**
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	public function getItemStackWithoutStackId() : ItemStack{
		[$id, $count, $meta] = $this->getItemStackHeader(false);

		if ($id === 0) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->getItemStackFooter($id, $meta, $count, false);
			}
			return ItemStack::null();
		}

		return $this->getItemStackFooter($id, $meta, $count, false);
	}

	public function putItemStackWithoutStackId(Item|ItemStack $itemStack) : void{
		if ($itemStack instanceof Item) {
			$itemStack = TypeConverter::getInstance()->coreItemStackToNet($itemStack, $this->protocol);
		}

		if($this->putItemStackHeader($itemStack, false)){
			$this->putItemStackFooter($itemStack, false);
		}
	}

	public function getItemStackWrapper() : ItemStackWrapper{
		[$id, $count, $meta] = $this->getItemStackHeader();

		if ($this->protocol >= ProtocolInfo::PROTOCOL_431 && ($id !== 0 || $this->protocol >= ProtocolInfo::PROTOCOL_2193)) {
			$hasNetId = $this->getBool();
			$stackId = $hasNetId ? $this->readServerItemStackId() : 0;
		}
		if($id === 0){
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->getItemStackFooter($id, $meta, $count);
			}
			return new ItemStackWrapper(0, ItemStack::null());
		}

		$itemStack = $this->getItemStackFooter($id, $meta, $count);

		return new ItemStackWrapper($stackId ?? 1, $itemStack);
	}

	public function putItemStackWrapper(Item|ItemStackWrapper $itemStackWrapper) : void{
		if ($itemStackWrapper instanceof Item) {
			$itemStackWrapper = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($itemStackWrapper, $this->protocol));
		}

		$itemStack = $itemStackWrapper->getItemStack();
		if($this->putItemStackHeader($itemStack)){
			if ($this->protocol >= ProtocolInfo::PROTOCOL_431) {
				$hasNetId = $itemStackWrapper->getStackId() !== 0;
				$this->putBool($hasNetId);
				if ($hasNetId) {
					$this->writeServerItemStackId($itemStackWrapper->getStackId());
				}
			}

			$this->putItemStackFooter($itemStack);
		}
	}

	public function getNetworkItemStackDescriptor(int $minimalProtocol = ProtocolInfo::PROTOCOL_1001) : ItemStackWrapper{
		if ($this->getProtocol() < $minimalProtocol) {
			return $this->getItemStackWrapper();
		}

		$id = $this->getSignedLShort();
		$count = $this->getLShort();
		$meta = $this->getUnsignedVarInt();

		if ($this->getBool()) {
			if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
				$this->getUnsignedVarInt();
			}
			$stackId = $this->readServerItemStackId();
		}

		$blockRuntimeId = $this->getUnsignedVarInt();

		$length = $this->getUnsignedVarInt();
		if ($length === 0) {
			[$compound, $canPlaceOn, $canDestroy, $shieldBlockingTick] = [null, [], [], null];
		} else {
			[$compound, $canPlaceOn, $canDestroy, $shieldBlockingTick] = $this->getItemStackExtraData($id, new NetworkBinaryStream($this->get($length)));
		}

		return new ItemStackWrapper($stackId ?? 0, new ItemStack($id, $meta, $count, $blockRuntimeId, $compound, $canPlaceOn, $canDestroy, $shieldBlockingTick));
	}

	public function putNetworkItemStackDescriptor(Item|ItemStackWrapper $itemStackWrapper, int $minimalProtocol = ProtocolInfo::PROTOCOL_1001) : void{
		if ($itemStackWrapper instanceof Item) {
			$itemStackWrapper = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($itemStackWrapper, $this->protocol));
		}

		if ($this->getProtocol() < $minimalProtocol) {
			$this->putItemStackWrapper($itemStackWrapper);
			return;
		}

		$itemStack = $itemStackWrapper->getItemStack();
		$this->putLShort($itemStack->getId());
		$this->putLShort($itemStack->getCount());
		$this->putUnsignedVarInt($itemStack->getMeta());

		$this->putBool($hasNetId = $itemStackWrapper->getStackId() !== 0);
		if($hasNetId){
			if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt(0);
			}
			$this->writeServerItemStackId($itemStackWrapper->getStackId());
		}

		$this->putUnsignedVarInt($itemStack->getBlockRuntimeId());

		if ($itemStack->getId() === 0) {
			$this->putUnsignedVarInt(0);
		} else {
			$extraData = new NetworkBinaryStream();
			$extraData->setProtocol($this->protocol);
			$this->putItemStackExtraData($itemStack, $extraData);
			$this->putString($extraData->getBuffer());
		}
	}

	public function getRecipeIngredient() : RecipeIngredient
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_554) {
			if ($this->protocol < ProtocolInfo::PROTOCOL_407) {
				$item = $this->getItemStackWithoutStackId();
				$id = $item->getId();
				$meta = $item->getMeta();
			} else {
				$id = $this->getVarInt();
				if ($id !== 0) {
					$meta = $this->getVarInt();
					$count = $this->getVarInt();
				}
			}

			$descriptor = new IntIdMetaItemDescriptor($id, $meta ?? 0);
		} elseif ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			return $this->getCraftingRecipeIngredient();
		} else {
			$descriptorType = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getUnsignedVarInt() : $this->getByte();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->getByte();
			}
			$descriptor = match ($descriptorType) {
				ItemDescriptorType::INT_ID_META => IntIdMetaItemDescriptor::read($this),
				ItemDescriptorType::STRING_ID_META => StringIdMetaItemDescriptor::read($this),
				ItemDescriptorType::TAG => TagItemDescriptor::read($this),
				ItemDescriptorType::MOLANG => MolangItemDescriptor::read($this),
				ItemDescriptorType::COMPLEX_ALIAS => ComplexAliasItemDescriptor::read($this),
				default => null
			};

			$count = $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->getSignedLShort() : $this->getVarInt();
		}

		return new RecipeIngredient($descriptor, $count ?? 0);
	}

	public function putRecipeIngredient(RecipeIngredient $ingredient) : void
	{
		$type = $ingredient->getDescriptor();
		if ($this->protocol < ProtocolInfo::PROTOCOL_554) {
			if (!($type instanceof IntIdMetaItemDescriptor)) {
				$this->putVarInt(0);
				return;
			}

			if ($this->protocol < ProtocolInfo::PROTOCOL_407) {
				$this->putItemStackWithoutStackId(new ItemStack(
					$type->getId(),
					$type->getMeta(),
					$ingredient->getCount(),
					0,
					null,
					[],
					[]
				));
			} else {
				$this->putVarInt($type->getId());
				$this->putVarInt($type->getMeta());
				$this->putVarInt($ingredient->getCount());
			}
		} elseif ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putCraftingRecipeIngredient($ingredient);
			return;
		} else {
			if ($this->protocol < ProtocolInfo::PROTOCOL_575 && $type instanceof ComplexAliasItemDescriptor) {
				$type = null;
			}

			$this->putByte($type?->getTypeId() ?? 0);
			$type?->write($this);
			$this->putVarInt($ingredient->getCount());
		}
	}

	private function getCraftingRecipeIngredient() : RecipeIngredient
	{
		if ($this->getUnsignedVarInt() === 0) {
			$this->getVarInt();
			return new RecipeIngredient(null, $this->getVarInt());
		}

		$descriptor = match ($this->getString()) {
			"name" => ItemNameDescriptor::read($this),
			"molang" => MolangItemDescriptor::read($this),
			"item_tag" => $this->readCraftingTagItemDescriptor(),
			default => null
		};

		return new RecipeIngredient($descriptor, $this->getVarInt());
	}

	private function readCraftingTagItemDescriptor() : TagItemDescriptor
	{
		$descriptor = TagItemDescriptor::read($this);
		$this->getVarInt();
		return $descriptor;
	}

	private function putCraftingRecipeIngredient(RecipeIngredient $ingredient) : void
	{
		$descriptor = $ingredient->getDescriptor();
		if ($descriptor === null) {
			$this->putUnsignedVarInt(0);
			$this->putVarInt(0x7fff);
			$this->putVarInt($ingredient->getCount());
			return;
		}

		$this->putUnsignedVarInt(1);
		if ($descriptor instanceof ItemNameDescriptor) {
			$this->putString("name");
			$descriptor->write($this);
		} elseif ($descriptor instanceof MolangItemDescriptor) {
			$this->putString("molang");
			$descriptor->write($this);
		} elseif ($descriptor instanceof TagItemDescriptor) {
			$this->putString("item_tag");
			$descriptor->write($this);
			$this->putVarInt(0x7fff);
		} else {
			throw new UnexpectedValueException("Unsupported crafting recipe ingredient descriptor " . $descriptor::class);
		}
		$this->putVarInt($ingredient->getCount());
	}

	public function getStackRequestRecipeIngredient() : RecipeIngredient
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
			return $this->getRecipeIngredient();
		}

		$descriptorType = $this->getUnsignedVarInt();
		$this->getByte();
		$descriptor = match ($descriptorType) {
			ItemNameDescriptor::ID => ItemNameDescriptor::read($this),
			ItemDescriptorType::MOLANG => MolangItemDescriptor::read($this),
			ItemDescriptorType::TAG => TagItemDescriptor::read($this),
			default => null
		};

		return new RecipeIngredient($descriptor, $this->getSignedLShort());
	}

	public function putStackRequestRecipeIngredient(RecipeIngredient $ingredient) : void
	{
		if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
			$this->putRecipeIngredient($ingredient);
			return;
		}

		$type = $ingredient->getDescriptor();
		$this->putUnsignedVarInt($type?->getTypeId() ?? 0);
		$this->putByte($type?->getTypeId() ?? 0);
		$type?->write($this);
		$this->putLShort($ingredient->getCount());
	}

	/**
	 * Decodes entity metadata from the stream.
	 *
	 * @param bool $types Whether to include metadata types along with values in the returned array
	 */
	public function getEntityMetadata(bool $types = true) : array
	{
		$count = $this->getUnsignedVarInt();
		if ($count > 128) {
			throw new UnexpectedValueException("Too many actor metadata: $count");
		}
		$data = [];
		for ($i = 0; $i < $count; ++$i) {
			$key = $this->getUnsignedVarInt();
			$type = $this->getUnsignedVarInt();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->getByte();
			}
			$value = null;
			switch ($type) {
				case Entity::DATA_TYPE_BYTE:
					$value = (ord($this->get(1)));
					break;
				case Entity::DATA_TYPE_SHORT:
					$value = $this->getSignedLShort();
					break;
				case Entity::DATA_TYPE_INT:
					$value = $this->getVarInt();
					break;
				case Entity::DATA_TYPE_FLOAT:
					$value = $this->getLFloat();
					break;
				case Entity::DATA_TYPE_STRING:
					$value = $this->getString();
					break;
				case Entity::DATA_TYPE_SLOT:
					if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
						$value = (new NetworkLittleEndianNBTStream())->read($this->buffer, false, $this->offset, 512);
					} else {
						$value = $this->getItemStackWithoutStackId();
					}
					break;
				case Entity::DATA_TYPE_POS:
					$value = new Vector3(0, 0, 0);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
						$this->getBlockPosition($value->x, $value->y, $value->z);
					} else {
						$this->getSignedBlockPosition($value->x, $value->y, $value->z);
					}
					break;
				case Entity::DATA_TYPE_LONG:
					$value = $this->getVarLong();
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					$value = $this->getVector3();
					break;
				default:
					throw new UnexpectedValueException("Invalid data type " . $type);
			}
			if ($types) {
				$data[$key] = [$type, $value];
			} else {
				$data[$key] = $value;
			}
		}

		return EntityMetadataTranslator::getInstance()->fromNetworkIds($data, $this->protocol);
	}

	/**
	 * Writes entity metadata to the packet buffer.
	 */
	public function putEntityMetadata(array $metadata) : void
	{
		$metadata = EntityMetadataTranslator::getInstance()->toNetworkIds($metadata, $this->protocol);

		$this->putUnsignedVarInt(count($metadata));
		foreach ($metadata as $key => $d) {
			$this->putUnsignedVarInt($key); //data key
			$this->putUnsignedVarInt($d[0]); //data type
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putByte($d[0]);
			}
			switch ($d[0]) {
				case Entity::DATA_TYPE_BYTE:
					$this->putByte($d[1]);
					break;
				case Entity::DATA_TYPE_SHORT:
					$this->putLShort($d[1]);
					break;
				case Entity::DATA_TYPE_INT:
					$this->putVarInt($d[1]);
					break;
				case Entity::DATA_TYPE_FLOAT:
					$this->putLFloat($d[1]);
					break;
				case Entity::DATA_TYPE_STRING:
					$this->putString($d[1]);
					break;
				case Entity::DATA_TYPE_SLOT:
					/** @var Item $item */
					$item = $d[1];
					if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
						$this->put((new NetworkLittleEndianNBTStream())->write($item->getNamedTag()));
					} else {
						$this->putItemStackWithoutStackId(TypeConverter::getInstance()->coreItemStackToNet($item, $this->protocol));
					}
					break;
				case Entity::DATA_TYPE_POS:
					$v = $d[1];
					if ($v !== null) {
						if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
							$this->putBlockPosition($v->x, $v->y, $v->z);
						} else {
							$this->putSignedBlockPosition($v->x, $v->y, $v->z);
						}
					} else {
						if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
							$this->putBlockPosition(0, 0, 0);
						} else {
							$this->putSignedBlockPosition(0, 0, 0);
						}
					}
					break;
				case Entity::DATA_TYPE_LONG:
					$this->putVarLong($d[1]);
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					$this->putVector3Nullable($d[1]);
					break;
				default:
					throw new UnexpectedValueException("Invalid data type " . $d[0]);
			}
		}
	}

	/**
	 * Reads a list of Attributes from the stream.
	 * @return Attribute[]
	 *
	 * @throws UnexpectedValueException if reading an attribute with an unrecognized name
	 */
	public function getAttributeList() : array
	{
		$list = [];
		$count = $this->getUnsignedVarInt();

		for ($i = 0; $i < $count; ++$i) {
			$min = $this->getLFloat();
			$max = $this->getLFloat();
			$current = $this->getLFloat();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
				$this->getLFloat(); //default min value
				$this->getLFloat(); //default max value
			}
			$default = $this->getLFloat();
			$name = $this->getString();
			$modifiers = [];
			if ($this->protocol >= ProtocolInfo::PROTOCOL_544) {
				for ($j = 0, $modifierCount = $this->getUnsignedVarInt(); $j < $modifierCount; $j++) {
					$modifiers[] = AttributeModifier::read($this);
				}
			}

			$attr = Attribute::getAttributeByName($name);
			if ($attr !== null) {
				$attr->setMinValue($min);
				$attr->setMaxValue($max);
				$attr->setValue($current);
				$attr->setDefaultValue($default);
				$attr->setModifiers($modifiers);

				$list[] = $attr;
			} else {
				throw new UnexpectedValueException("Unknown attribute type \"$name\"");
			}
		}

		return $list;
	}

	/**
	 * Writes a list of Attributes to the packet buffer using the standard format.
	 */
	public function putAttributeList(Attribute ...$attributes) : void
	{
		$this->putUnsignedVarInt(count($attributes));
		foreach ($attributes as $attribute) {
			$this->putLFloat($attribute->getMinValue());
			$this->putLFloat($attribute->getMaxValue());
			$this->putLFloat($attribute->getValue());
			if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
				$this->putLFloat($attribute->getMinValue()); //default min value
				$this->putLFloat($attribute->getMaxValue()); //default max value
			}
			$this->putLFloat($attribute->getDefaultValue());
			$this->putString($attribute->getName());
			if ($this->protocol >= ProtocolInfo::PROTOCOL_544) {
				$this->putUnsignedVarInt(count($attribute->getModifiers()));
				foreach ($attribute->getModifiers() as $modifier) {
					$modifier->write($this);
				}
			}
		}
	}

	/**
	 * Reads and returns an EntityUniqueID
	 */
	final public function getEntityUniqueId() : int
	{
		return $this->getVarLong();
	}

	/**
	 * Writes an EntityUniqueID
	 */
	public function putEntityUniqueId(int $eid) : void
	{
		$this->putVarLong($eid);
	}

	/**
	 * Reads and returns an EntityRuntimeID
	 */
	final public function getEntityRuntimeId() : int
	{
		return $this->getUnsignedVarLong();
	}

	/**
	 * Writes an EntityRuntimeID
	 */
	public function putEntityRuntimeId(int $eid) : void
	{
		$this->putUnsignedVarLong($eid);
	}

	/**
	 * Reads a block position with a signed Y coordinate.
	 *
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getBlockPosition(&$x, &$y, &$z) : void
	{
		$x = $this->getVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_944) {
			$y = $this->getVarInt();
		} else {
			$y = $this->getUnsignedVarInt();
		}

		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with a signed Y coordinate.
	 */
	public function putBlockPosition(int $x, int $y, int $z) : void
	{
		$this->putVarInt($x);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_944) {
			$this->putVarInt($y);
		} else {
			$this->putUnsignedVarInt($y);
		}

		$this->putVarInt($z);
	}

	/**
	 * Reads a block position with a signed Y coordinate.
	 *
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 * @deprecated Use {@see getBlockPosition()} instead.
	 */
	public function getSignedBlockPosition(&$x, &$y, &$z) : void
	{
		$x = $this->getVarInt();
		$y = $this->getVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with a signed Y coordinate.
	 * @deprecated Use {@see putBlockPosition()} instead.
	 */
	public function putSignedBlockPosition(int $x, int $y, int $z) : void
	{
		$this->putVarInt($x);
		$this->putVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Reads a floating-point Vector3 object with coordinates rounded to 4 decimal places.
	 */
	public function getVector3() : Vector3
	{
		return new Vector3(
			$this->getLFloat(),
			$this->getLFloat(),
			$this->getLFloat()
		);
	}

	/**
	 * Reads a floating-point Vector2 object with coordinates rounded to 4 decimal places.
	 *
	 * @throws BinaryDataException
	 */
	public function getVector2() : Vector2
	{
		$x = $this->getLFloat();
		$y = $this->getLFloat();
		return new Vector2($x, $y);
	}

	/**
	 * Writes a floating-point Vector3 object, or 3x zero if null is given.
	 *
	 * Note: ONLY use this where it is reasonable to allow not specifying the vector.
	 * For all other purposes, use the non-nullable version.
	 *
	 * @see NetworkBinaryStream::putVector3()
	 */
	public function putVector3Nullable(?Vector3 $vector) : void
	{
		if ($vector) {
			$this->putVector3($vector);
		} else {
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
		}
	}

	/**
	 * Writes a floating-point Vector3 object
	 */
	public function putVector3(Vector3 $vector) : void
	{
		$this->putLFloat($vector->x);
		$this->putLFloat($vector->y);
		$this->putLFloat($vector->z);
	}

	/**
	 * Writes a floating-point Vector2 object
	 */
	public function putVector2(Vector2 $vector2) : void
	{
		$this->putLFloat($vector2->x);
		$this->putLFloat($vector2->y);
	}

	public function getByteRotation() : float
	{
		return (float) ((ord($this->get(1))) * (360 / 256));
	}

	public function putByteRotation(float $rotation) : void
	{
		($this->buffer .= chr((int) ($rotation / (360 / 256))));
	}

	/**
	 * Reads gamerules
	 * TODO: implement this properly
	 *
	 * @return array, members are in the structure [name => [type, value, isPlayerModifiable]]
	 */
	public function getGameRules(bool $isStartGame) : array
	{
		$count = $this->getUnsignedVarInt();
		$rules = [];
		for ($i = 0; $i < $count; ++$i) {
			$name = $this->getString();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_440) {
				$isPlayerModifiable = $this->getBool();
			}

			$type = $this->getUnsignedVarInt();
			$value = null;
			switch ($type) {
				case GameRuleType::BOOL:
					$value = $this->getBool();
					break;
				case GameRuleType::INT:
					if ($this->protocol >= ProtocolInfo::PROTOCOL_844) {
						$value = $isStartGame ? $this->getUnsignedVarInt() : $this->getLInt();
					} else {
						$value = $this->getUnsignedVarInt();
					}
					break;
				case GameRuleType::FLOAT:
					$value = $this->getLFloat();
					break;
			}

			$rules[$name] = [$type, $value, $isPlayerModifiable ?? false];
		}

		return $rules;
	}

	/**
	 * Writes a gamerule array, members should be in the structure [name => [type, value, isPlayerModifiable]]
	 * TODO: implement this properly
	 */
	public function putGameRules(array $rules, bool $isStartGame) : void
	{
		$this->putUnsignedVarInt(count($rules));
		foreach ($rules as $name => $rule) {
			$this->putString($name);
			if ($this->protocol >= ProtocolInfo::PROTOCOL_440) {
				$this->putBool($rule[2] ?? false);
			}
			$this->putUnsignedVarInt($rule[0]);
			switch ($rule[0]) {
				case GameRuleType::BOOL:
					$this->putBool($rule[1]);
					break;
				case GameRuleType::INT:
					if ($this->protocol >= ProtocolInfo::PROTOCOL_844) {
						if ($isStartGame) {
							$this->putUnsignedVarInt($rule[1]);
						} else {
							$this->putLInt($rule[1]);
						}
					} else {
						$this->putUnsignedVarInt($rule[1]);
					}
					break;
				case GameRuleType::FLOAT:
					$this->putLFloat($rule[1]);
					break;
			}
		}
	}

	protected function getEntityLink() : EntityLink
	{
		$link = new EntityLink();

		$link->fromEntityUniqueId = $this->getEntityUniqueId();
		$link->toEntityUniqueId = $this->getEntityUniqueId();
		$link->type = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$link->immediate = $this->getBool();
			$link->causedByRider = $this->getBool();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
				$link->vehicleAngularVelocity = $this->getLFloat();
			}
		}

		return $link;
	}

	protected function putEntityLink(EntityLink $link) : void
	{
		$this->putEntityUniqueId($link->fromEntityUniqueId);
		$this->putEntityUniqueId($link->toEntityUniqueId);
		$this->putByte($link->type);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putBool($link->immediate);
			$this->putBool($link->causedByRider);
			if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
				$this->putLFloat($link->vehicleAngularVelocity);
			}
		}
	}

	protected function getCommandOriginData() : CommandOriginData{
		$result = new CommandOriginData();

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$result->type = OriginDataType::fromName($this->getString());
		} else {
			$result->type = OriginDataType::fromPacket($this->getUnsignedVarInt());
		}

		$result->uuid = $this->getUUID();
		$result->requestId = $this->getString();

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$result->playerActorUniqueId = $this->getLLong();
		} else {
			if ($result->type === OriginDataType::ORIGIN_DEV_CONSOLE || $result->type === OriginDataType::ORIGIN_TEST) {
				$result->playerActorUniqueId = $this->getVarLong();
			}
		}

		return $result;
	}

	protected function putCommandOriginData(CommandOriginData $data) : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putString($data->type->getName());
		} else {
			$this->putUnsignedVarInt($data->type->value);
		}

		$this->putUUID($data->uuid);
		$this->putString($data->requestId);

		if ($this->protocol >= ProtocolInfo::PROTOCOL_897) {
			$this->putLLong($data->playerActorUniqueId);
		} else {
			if ($data->type === OriginDataType::ORIGIN_DEV_CONSOLE || $data->type === OriginDataType::ORIGIN_TEST) {
				$this->putVarLong($data->playerActorUniqueId);
			}
		}
	}

	protected function getStructureSettings() : StructureSettings
	{
		$result = new StructureSettings();

		$result->paletteName = $this->getString();

		$result->ignoreEntities = $this->getBool();
		$result->ignoreBlocks = $this->getBool();

		$this->getBlockPosition($result->structureSizeX, $result->structureSizeY, $result->structureSizeZ);
		$this->getBlockPosition($result->structureOffsetX, $result->structureOffsetY, $result->structureOffsetZ);

		$result->lastTouchedByPlayerID = $this->getEntityUniqueId();
		$result->rotation = $this->getByte();
		$result->mirror = $this->getByte();
		$result->integrityValue = $this->getFloat();
		$result->integritySeed = $this->getInt();

		return $result;
	}

	protected function putStructureSettings(StructureSettings $structureSettings) : void
	{
		$this->putString($structureSettings->paletteName);

		$this->putBool($structureSettings->ignoreEntities);
		$this->putBool($structureSettings->ignoreBlocks);

		$this->putBlockPosition($structureSettings->structureSizeX, $structureSettings->structureSizeY, $structureSettings->structureSizeZ);
		$this->putBlockPosition($structureSettings->structureOffsetX, $structureSettings->structureOffsetY, $structureSettings->structureOffsetZ);

		$this->putEntityUniqueId($structureSettings->lastTouchedByPlayerID);
		$this->putByte($structureSettings->rotation);
		$this->putByte($structureSettings->mirror);
		$this->putFloat($structureSettings->integrityValue);
		$this->putInt($structureSettings->integritySeed);
	}

	protected function getStructureEditorData() : StructureEditorData
	{
		$result = new StructureEditorData();

		$result->structureName = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_776) {
			$result->filteredStructureName = $this->getString();
		}
		$result->structureDataField = $this->getString();

		$result->includePlayers = $this->getBool();
		$result->showBoundingBox = $this->getBool();

		$result->structureBlockType = $this->getVarInt();
		$result->structureSettings = $this->getStructureSettings();
		$result->structureRedstoneSaveMode = $this->getVarInt();

		return $result;
	}

	protected function putStructureEditorData(StructureEditorData $structureEditorData) : void
	{
		$this->putString($structureEditorData->structureName);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_776) {
			$this->putString($structureEditorData->filteredStructureName);
		}
		$this->putString($structureEditorData->structureDataField);

		$this->putBool($structureEditorData->includePlayers);
		$this->putBool($structureEditorData->showBoundingBox);

		$this->putVarInt($structureEditorData->structureBlockType);
		$this->putStructureSettings($structureEditorData->structureSettings);
		$this->putVarInt($structureEditorData->structureRedstoneSaveMode);
	}

	public function getNbtRoot() : NamedTag
	{
		$offset = $this->getOffset();
		try {
			$result = (new NetworkLittleEndianNBTStream())->read($this->getBuffer(), false, $offset, 512);
			assert($result instanceof NamedTag, "doMultiple is false so we should definitely have a NamedTag here");
			return $result;
		} finally {
			$this->setOffset($offset);
		}
	}

	public function getNbtCompoundRoot() : CompoundTag
	{
		$root = $this->getNbtRoot();
		if (!($root instanceof CompoundTag)) {
			throw new UnexpectedValueException("Expected TAG_Compound root");
		}
		return $root;
	}

	public function readRecipeNetId() : int
	{
		return $this->getUnsignedVarInt();
	}

	public function writeRecipeNetId(int $id) : void
	{
		$this->putUnsignedVarInt($id);
	}

	public function readCreativeItemNetId() : int
	{
		return $this->getUnsignedVarInt();
	}

	public function writeCreativeItemNetId(int $id) : void
	{
		$this->putUnsignedVarInt($id);
	}

	/**
	 * This is a union of ItemStackRequestId, LegacyItemStackRequestId, and ServerItemStackId, used in serverbound
	 * packets to allow the client to refer to server known items, or items which may have been modified by a previous
	 * as-yet unacknowledged request from the client.
	 *
	 * - Server itemstack ID is positive
	 * - InventoryTransaction "legacy" request ID is negative and even
	 * - ItemStackRequest request ID is negative and odd
	 * - 0 refers to an empty itemstack (air)
	 */
	public function readItemStackNetIdVariant() : int
	{
		return $this->getVarInt();
	}

	/**
	 * This is a union of ItemStackRequestId, LegacyItemStackRequestId, and ServerItemStackId, used in serverbound
	 * packets to allow the client to refer to server known items, or items which may have been modified by a previous
	 * as-yet unacknowledged request from the client.
	 */
	public function writeItemStackNetIdVariant(int $id) : void
	{
		$this->putVarInt($id);
	}

	public function readItemStackRequestId() : int
	{
		return $this->getVarInt();
	}

	public function writeItemStackRequestId(int $id) : void
	{
		$this->putVarInt($id);
	}

	public function readLegacyItemStackRequestId() : int
	{
		return $this->getVarInt();
	}

	public function writeLegacyItemStackRequestId(int $id) : void
	{
		$this->putVarInt($id);
	}

	public function readServerItemStackId() : int
	{
		return $this->getVarInt();
	}

	public function writeServerItemStackId(int $id) : void
	{
		$this->putVarInt($id);
	}

	/**
	 * @phpstan-template T
	 * @phpstan-param \Closure() : T $reader
	 * @phpstan-return T|null
	 */
	public function getOptional(\Closure $reader) : mixed
	{
		if ($this->getBool()) {
			return $reader();
		}
		return null;
	}

	/**
	 * @phpstan-template T
	 * @phpstan-param T|null $value
	 * @phpstan-param \Closure(T) : void $writer
	 */
	public function putOptional(mixed $value, \Closure $writer) : void
	{
		if ($value !== null) {
			$this->putBool(true);
			$writer($value);
		} else {
			$this->putBool(false);
		}
	}
}
