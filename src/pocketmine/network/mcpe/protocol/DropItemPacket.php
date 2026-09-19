<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

class DropItemPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::DROP_ITEM_PACKET;

	public int $type;
	public ItemStack $item;

	protected function decodePayload() : void
	{
		$this->type = $this->getByte();
		$this->item = $this->getItemStackWithoutStackId();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->type);
		$this->putItemStackWithoutStackId($this->item);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleDropItem($this);
	}

}
