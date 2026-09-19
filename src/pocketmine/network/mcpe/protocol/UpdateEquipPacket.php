<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class UpdateEquipPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_EQUIP_PACKET;

	/** @var int */
	public $windowId;
	/** @var int */
	public $windowType;
	/** @var int */
	public $windowSlotCount; //useless, seems to be part of a standard container header
	/** @var int */
	public $entityUniqueId;
	/** @var string */
	public $namedtag;

	protected function decodePayload() : void
	{
		$this->windowId = $this->getByte();
		$this->windowType = $this->getByte();
		$this->windowSlotCount = $this->getVarInt();
		$this->entityUniqueId = $this->getEntityUniqueId();
		$this->namedtag = $this->getRemaining();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->windowId);
		$this->putByte($this->windowType);
		$this->putVarInt($this->windowSlotCount);
		$this->putEntityUniqueId($this->entityUniqueId);
		$this->put($this->namedtag);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateEquip($this);
	}
}
