<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class NetworkStackLatencyPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::NETWORK_STACK_LATENCY_PACKET;

	public int $timestamp;
	public bool $needResponse;

	protected function decodePayload() : void
	{
		$this->timestamp = $this->getLLong();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->needResponse = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putLLong($this->timestamp);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putBool($this->needResponse);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleNetworkStackLatency($this);
	}
}
