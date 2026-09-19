<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class TransferPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::TRANSFER_PACKET;

	public string $address;
	public int $port = 19132;
	public bool $reloadWorld = false;

	protected function decodePayload() : void
	{
		$this->address = $this->getString();
		$this->port = $this->getLShort();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
			$this->reloadWorld = $this->getBool();
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->address);
		$this->putLShort($this->port);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_729) {
			$this->putBool($this->reloadWorld);
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putBool(false);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleTransfer($this);
	}
}
