<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\DisconnectFailReason;

class DisconnectPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::DISCONNECT_PACKET;

	public int $reason = DisconnectFailReason::UNKNOWN;
	public bool $hideDisconnectionScreen = false;
	public string $message = "";
	public string $filteredMessage = "";

	public function canBeSentBeforeLogin() : bool
	{
		return true;
	}

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_622) {
			$this->reason = $this->getVarInt();
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_975) {
			$this->hideDisconnectionScreen = $this->getUnsignedVarInt() === 1;
		} else {
			$this->hideDisconnectionScreen = $this->getBool();
		}

		if (!$this->hideDisconnectionScreen) {
			$this->message = $this->getString();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
				$this->filteredMessage = $this->getString();
			}
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_622) {
			$this->putVarInt($this->reason);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_975) {
			$this->putUnsignedVarInt($this->hideDisconnectionScreen ? 1 : 0);
		} else {
			$this->putBool($this->hideDisconnectionScreen);
		}

		if (!$this->hideDisconnectionScreen) {
			$this->putString($this->message);
			if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
				$this->putString($this->filteredMessage);
			}
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleDisconnect($this);
	}
}
