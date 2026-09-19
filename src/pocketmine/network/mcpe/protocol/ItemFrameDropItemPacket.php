<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ItemFrameDropItemPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::ITEM_FRAME_DROP_ITEM_PACKET;

	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleItemFrameDropItem($this);
	}
}
