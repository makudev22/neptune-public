<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ContainerClosePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_CLOSE_PACKET;

	/** @var int */
	public $windowId;
	/** @var int */
	public $windowType;
	/** @var bool */
	public $server = false;

	protected function decodePayload() : void
	{
		$this->windowId = $this->getByte();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_685) {
				$this->windowType = $this->getByte();
			}
			$this->server = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->windowId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_685) {
				$this->putByte($this->windowType);
			}
			$this->putBool($this->server);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleContainerClose($this);
	}
}
