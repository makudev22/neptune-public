<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ContainerOpenPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_OPEN_PACKET;

	/** @var int */
	public $windowId;
	/** @var int */
	public $type;
	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;
	/** @var int */
	public $entityUniqueId = -1;

	protected function decodePayload() : void
	{
		$this->windowId = $this->getByte();
		$this->type = $this->getByte();
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->entityUniqueId = $this->getEntityUniqueId();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->windowId);
		$this->putByte($this->type);
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->putEntityUniqueId($this->entityUniqueId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleContainerOpen($this);
	}
}
