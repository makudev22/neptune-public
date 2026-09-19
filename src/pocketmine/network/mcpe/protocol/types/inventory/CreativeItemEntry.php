<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class CreativeItemEntry
{
	public function __construct(
		private int $entryId,
		private ItemStack $item,
		private readonly int $groupId
	) {
	}

	public function getEntryId() : int
	{
		return $this->entryId;
	}

	public function getItem() : ItemStack
	{
		return $this->item;
	}

	public function getGroupId() : int
	{
		return $this->groupId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$entryId = $in->readCreativeItemNetId();
		$item = $in->getItemStackWithoutStackId();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_776) {
			$groupId = $in->getUnsignedVarInt();
		}
		return new self($entryId, $item, $groupId ?? 0);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->writeCreativeItemNetId($this->entryId);
		$out->putItemStackWithoutStackId($this->item);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_776) {
			$out->putUnsignedVarInt($this->groupId);
		}
	}
}
