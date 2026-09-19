<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ClientboundUpdateSoundDataPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private string $soundEvent;

	/**
	 * @generate-create-func
	 */
	public static function create(int $serverSoundHandle, string $soundEvent) : self{
		$result = new self();
		$result->serverSoundHandle = $serverSoundHandle;
		$result->soundEvent = $soundEvent;
		return $result;
	}

	public function getServerSoundHandle() : int{ return $this->serverSoundHandle; }

	public function getSoundEvent() : string{ return $this->soundEvent; }

	protected function decodePayload() : void{
		$this->serverSoundHandle = $this->getLLong();
		$this->soundEvent = $this->getString();
	}

	protected function encodePayload() : void{
		$this->putLLong($this->serverSoundHandle);
		$this->putString($this->soundEvent);
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleClientboundUpdateSoundData($this);
	}
}
