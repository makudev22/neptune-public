<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ServerboundDataStorePacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_DATA_STORE_PACKET;

	/**
	 * @generate-create-func
	 */
	public static function create() : self{
		return new self();
	}

	protected function decodePayload() : void{
		//TODO
	}

	protected function encodePayload() : void{
		//TODO
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleServerboundDataStore($this);
	}
}
