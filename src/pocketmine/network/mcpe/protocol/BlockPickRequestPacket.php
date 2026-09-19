<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class BlockPickRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::BLOCK_PICK_REQUEST_PACKET;

	/** @var int */
	public $blockX;
	/** @var int */
	public $blockY;
	/** @var int */
	public $blockZ;
	/** @var bool */
	public $addUserData = false;
	/** @var int */
	public $hotbarSlot;

	protected function decodePayload() : void
	{
		$this->getSignedBlockPosition($this->blockX, $this->blockY, $this->blockZ);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->addUserData = $this->getBool();
		}
		$this->hotbarSlot = $this->getByte();
	}

	protected function encodePayload() : void
	{
		$this->putSignedBlockPosition($this->blockX, $this->blockY, $this->blockZ);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putBool($this->addUserData);
		}
		$this->putByte($this->hotbarSlot);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleBlockPickRequest($this);
	}
}
