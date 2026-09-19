<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class PlaySoundPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAY_SOUND_PACKET;

	public string $soundName;
	public float $x;
	public float $y;
	public float $z;
	public float $volume;
	public float $pitch;
	public int $loopCount = 0;
	public bool $bypassListenerRangeCheck = false;
	public ?int $serverSoundHandle = null;
	public ?float $playbackPositionSeconds = null;

	protected function decodePayload() : void
	{
		$this->soundName = $this->getString();
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->x /= 8;
		$this->y /= 8;
		$this->z /= 8;
		$this->volume = $this->getLFloat();
		$this->pitch = $this->getLFloat();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->loopCount = $this->getVarInt();
			$this->bypassListenerRangeCheck = $this->getBool();
		}
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->serverSoundHandle = $this->getOptional($this->getLLong(...));
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->playbackPositionSeconds = $this->getOptional($this->getLFloat(...));
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->soundName);
		$this->putBlockPosition((int) ($this->x * 8), (int) ($this->y * 8), (int) ($this->z * 8));
		$this->putLFloat($this->volume);
		$this->putLFloat($this->pitch);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putVarInt($this->loopCount);
			$this->putBool($this->bypassListenerRangeCheck);
		}
		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->putOptional($this->serverSoundHandle, $this->putLLong(...));
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putOptional($this->playbackPositionSeconds, $this->putLFloat(...));
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePlaySound($this);
	}
}
