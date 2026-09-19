<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class StopSoundPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::STOP_SOUND_PACKET;

	public string $soundName;
	public bool $stopAll;
	public bool $stopMusicLegacy = true;

	protected function decodePayload() : void
	{
		$this->soundName = $this->getString();
		$this->stopAll = $this->getBool();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
			$this->stopMusicLegacy = $this->getBool();
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->soundName);
		$this->putBool($this->stopAll);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_712) {
			$this->putBool($this->stopMusicLegacy);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleStopSound($this);
	}
}
