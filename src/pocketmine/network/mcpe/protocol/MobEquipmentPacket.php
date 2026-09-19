<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\item\Item;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class MobEquipmentPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::MOB_EQUIPMENT_PACKET;

	public int $entityRuntimeId;
	public Item|ItemStackWrapper $item;
	public int $inventorySlot;
	public int $hotbarSlot;
	public int $windowId = ContainerIds::INVENTORY;

	/**
	 * @generate-create-func
	 */
	public static function create(int $entityRuntimeId, Item|ItemStackWrapper $item, int $inventorySlot, int $hotbarSlot, int $windowId) : self{
		$result = new self();
		$result->entityRuntimeId = $entityRuntimeId;
		$result->item = $item;
		$result->inventorySlot = $inventorySlot;
		$result->hotbarSlot = $hotbarSlot;
		$result->windowId = $windowId;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->item = $this->getNetworkItemStackDescriptor(ProtocolInfo::PROTOCOL_975);
		$this->inventorySlot = $this->getByte();
		$this->hotbarSlot = $this->getByte();
		$this->windowId = $this->getByte();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putNetworkItemStackDescriptor($this->item, ProtocolInfo::PROTOCOL_975);
		$this->putByte($this->inventorySlot);
		$this->putByte($this->hotbarSlot);
		$this->putByte($this->windowId);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleMobEquipment($this);
	}
}
