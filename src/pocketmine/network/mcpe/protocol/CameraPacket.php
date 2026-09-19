<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class CameraPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CAMERA_PACKET;

	/** @var int */
	public $cameraUniqueId;
	/** @var int */
	public $playerUniqueId;

	protected function decodePayload() : void
	{
		$this->cameraUniqueId = $this->getEntityUniqueId();
		$this->playerUniqueId = $this->getEntityUniqueId();
	}

	protected function encodePayload() : void
	{
		$this->putEntityUniqueId($this->cameraUniqueId);
		$this->putEntityUniqueId($this->playerUniqueId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCamera($this);
	}
}
