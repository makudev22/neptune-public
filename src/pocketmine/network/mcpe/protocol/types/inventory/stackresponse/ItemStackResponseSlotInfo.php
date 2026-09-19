<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackresponse;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class ItemStackResponseSlotInfo
{
	public function __construct(
		private int $slot,
		private int $hotbarSlot,
		private int $count,
		private int $itemStackId,
		private string $customName,
		private string $filteredCustomName,
		private int $durabilityCorrection
	) {
	}

	public function getSlot() : int
	{
		return $this->slot;
	}

	public function getHotbarSlot() : int
	{
		return $this->hotbarSlot;
	}

	public function getCount() : int
	{
		return $this->count;
	}

	public function getItemStackId() : int
	{
		return $this->itemStackId;
	}

	public function getCustomName() : string
	{
		return $this->customName;
	}

	public function getFilteredCustomName() : string
	{
		return $this->filteredCustomName;
	}

	public function getDurabilityCorrection() : int
	{
		return $this->durabilityCorrection;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$slot = $in->getByte();
		$hotbarSlot = $in->getByte();
		$count = $in->getByte();
		$itemStackId = $in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 ? $in->getOptional($in->readServerItemStackId(...)) ?? 0 : $in->readServerItemStackId();
		$customName = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$filteredCustomName = $in->getOptional($in->getString(...)) ?? $customName;
		} elseif ($in->getProtocol() >= ProtocolInfo::PROTOCOL_766) {
			$filteredCustomName = $in->getString();
		}
		$durabilityCorrection = $in->getVarInt();
		return new self($slot, $hotbarSlot, $count, $itemStackId, $customName, $filteredCustomName ?? $customName, $durabilityCorrection);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->slot);
		$out->putByte($this->hotbarSlot);
		$out->putByte($this->count);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putOptional($this->itemStackId > 0 ? $this->itemStackId : null, $out->writeServerItemStackId(...));
		} else {
			$out->writeServerItemStackId($this->itemStackId);
		}
		$out->putString($this->customName);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putOptional($this->filteredCustomName !== $this->customName ? $this->filteredCustomName : null, $out->putString(...));
		} elseif ($out->getProtocol() >= ProtocolInfo::PROTOCOL_766) {
			$out->putString($this->filteredCustomName);
		}
		$out->putVarInt($this->durabilityCorrection);
	}
}
