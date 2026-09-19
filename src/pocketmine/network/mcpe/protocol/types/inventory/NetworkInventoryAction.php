<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use InvalidArgumentException;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class NetworkInventoryAction
{
	public const SOURCE_CONTAINER = 0;
	public const SOURCE_GLOBAL = 1;
	public const SOURCE_WORLD = 2; //drop/pickup item entity
	public const SOURCE_CREATIVE = 3;
	public const SOURCE_UNTRACKED_INTERACTION_UI = 100;
	public const SOURCE_TODO = 99999;

	/**
	 * Fake window IDs for the SOURCE_TODO type (99999)
	 *
	 * These identifiers are used for inventory source types which are not currently implemented server-side in MCPE.
	 * As a general rule of thumb, anything that doesn't have a permanent inventory is client-side. These types are
	 * to allow servers to track what is going on in client-side windows.
	 *
	 * Expect these to change in the future.
	 */
	public const SOURCE_TYPE_CRAFTING_ADD_INGREDIENT = -2;
	public const SOURCE_TYPE_CRAFTING_REMOVE_INGREDIENT = -3;
	public const SOURCE_TYPE_CRAFTING_RESULT = -4;
	public const SOURCE_TYPE_CRAFTING_USE_INGREDIENT = -5;

	public const SOURCE_TYPE_FAKE_INVENTORY_INPUT = -10;
	public const SOURCE_TYPE_FAKE_INVENTORY_MATERIAL = -11;
	public const SOURCE_TYPE_FAKE_INVENTORY_RESULT = -12;

	public const SOURCE_TYPE_ENCHANT_INPUT = -15;
	public const SOURCE_TYPE_ENCHANT_MATERIAL = -16;
	public const SOURCE_TYPE_ENCHANT_OUTPUT = -17;

	public const SOURCE_TYPE_TRADING_INPUT_1 = -20;
	public const SOURCE_TYPE_TRADING_INPUT_2 = -21;
	public const SOURCE_TYPE_TRADING_USE_INPUTS = -22;
	public const SOURCE_TYPE_TRADING_OUTPUT = -23;

	public const SOURCE_TYPE_BEACON = -24;

	/** Any client-side window dropping its contents when the player closes it */
	public const SOURCE_TYPE_CONTAINER_DROP_CONTENTS = -100;

	public const ACTION_MAGIC_SLOT_CREATIVE_DELETE_ITEM = 0;
	public const ACTION_MAGIC_SLOT_CREATIVE_CREATE_ITEM = 1;

	public const ACTION_MAGIC_SLOT_DROP_ITEM = 0;
	public const ACTION_MAGIC_SLOT_PICKUP_ITEM = 1;

	public int $sourceType;
	public ?int $windowId = null;
	public ?int $sourceFlags = null;
	public int $inventorySlot;
	public ItemStackWrapper $oldItem;
	public ItemStackWrapper $newItem;

	public function read(NetworkBinaryStream $in, bool $legacyTransaction, bool $hasItemStackIds) : self {
		$this->sourceType = $in->getUnsignedVarInt();

		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 && $legacyTransaction) {
			if ($in->getBool()) {
				$this->windowId = $in->getOptional($in->getByte(...));
			}
			if ($in->getBool()) {
				$this->sourceFlags = $in->getOptional($in->getUnsignedVarInt(...));
			}
		} elseif ($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			if(!$in->getBool()){
				throw new PacketDecodeException("Inconsistent optional state for windowId");
			}
			$this->windowId = $in->getOptional($in->getByte(...));

			if(!$in->getBool()){
				throw new PacketDecodeException("Inconsistent optional state for sourceFlags");
			}
			$this->sourceFlags = $in->getOptional($in->getUnsignedVarInt(...));
		} else {
			switch ($this->sourceType) {
				case self::SOURCE_TODO:
				case self::SOURCE_UNTRACKED_INTERACTION_UI:
				case self::SOURCE_CONTAINER:
					$this->windowId = $in->getVarInt();
					break;
				case self::SOURCE_WORLD:
					$this->sourceFlags = $in->getUnsignedVarInt();
					break;
				case self::SOURCE_CREATIVE:
					break;
				default:
					throw new PacketDecodeException("Unknown inventory action source type $this->sourceType");
			}
		}

		$this->inventorySlot = $in->getUnsignedVarInt();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$this->oldItem = $in->getNetworkItemStackDescriptor();
			$this->newItem = $in->getNetworkItemStackDescriptor();
		} else {
			$this->oldItem = $in->getItemStackWrapper();
			$this->newItem = $in->getItemStackWrapper();
		}

		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407 && $in->getProtocol() < ProtocolInfo::PROTOCOL_431 && $hasItemStackIds) {
			$this->newItem = new ItemStackWrapper($in->readServerItemStackId(), $this->newItem->getItemStack());
		}

		return $this;
	}

	public function write(NetworkBinaryStream $out, bool $legacyTransaction, bool $hasItemStackIds) : void{
		$out->putUnsignedVarInt($this->sourceType);

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193 && $legacyTransaction) {
			switch ($this->sourceType) {
				case self::SOURCE_TODO:
				case self::SOURCE_UNTRACKED_INTERACTION_UI:
				case self::SOURCE_CONTAINER:
					if ($this->windowId === null) {
						throw new \LogicException("WindowID must be set for a container action");
					}
					$out->putBool(true);
					$out->putOptional($this->windowId, $out->putByte(...));
					$out->putBool(false);
					break;
				case self::SOURCE_WORLD:
					if ($this->sourceFlags === null) {
						throw new \LogicException("SourceFlags must be set for a world action");
					}
					$out->putBool(false);
					$out->putBool(true);
					$out->putOptional($this->sourceFlags, $out->putUnsignedVarInt(...));
					break;
				case self::SOURCE_CREATIVE:
					break;
				default:
					$out->putBool(false);
					$out->putBool(false);
					break;
			}
		} elseif ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$out->putBool(true);
			$out->putOptional($this->windowId, $out->putByte(...));

			$out->putBool(true);
			$out->putOptional($this->sourceFlags, $out->putUnsignedVarInt(...));
		} else {
			switch ($this->sourceType) {
				case self::SOURCE_TODO:
				case self::SOURCE_UNTRACKED_INTERACTION_UI:
				case self::SOURCE_CONTAINER:
					if ($this->windowId === null) {
						throw new \LogicException("WindowID must be set for SOURCE_CONTAINER");
					}
					$out->putVarInt($this->windowId);
					break;
				case self::SOURCE_WORLD:
					if ($this->sourceFlags === null) {
						throw new \LogicException("SourceFlags must be set for SOURCE_WORLD");
					}
					$out->putUnsignedVarInt($this->sourceFlags);
					break;
				case self::SOURCE_CREATIVE:
					break;
				default:
					throw new InvalidArgumentException("Unknown inventory action source type $this->sourceType");
			}
		}

		$out->putUnsignedVarInt($this->inventorySlot);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001 && $legacyTransaction) {
			$out->putNetworkItemStackDescriptor($this->oldItem);
			$out->putNetworkItemStackDescriptor($this->newItem);
		} else {
			$out->putItemStackWrapper($this->oldItem);
			$out->putItemStackWrapper($this->newItem);
		}

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407 && $out->getProtocol() < ProtocolInfo::PROTOCOL_431 && $hasItemStackIds) {
			$out->writeServerItemStackId($this->newItem->getStackId());
		}
	}
}
