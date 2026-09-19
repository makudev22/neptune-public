<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class NpcRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::NPC_REQUEST_PACKET;

	/** @var int */
	public $entityRuntimeId;
	/** @var int */
	public $requestType;
	/** @var string */
	public $commandString;
	/** @var int */
	public $actionType;
	/** @var string */
	public $sceneName;

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->requestType = $this->getByte();
		$this->commandString = $this->getString();
		$this->actionType = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_448) {
			$this->sceneName = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putByte($this->requestType);
		$this->putString($this->commandString);
		$this->putByte($this->actionType);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_448) {
			$this->putString($this->sceneName);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleNpcRequest($this);
	}
}
