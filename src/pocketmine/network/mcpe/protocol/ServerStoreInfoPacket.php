<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ClientStoreEntrypointConfig;

class ServerStoreInfoPacket extends DataPacket {
	public const NETWORK_ID = ProtocolInfo::SERVER_STORE_INFO_PACKET;

	private ?ClientStoreEntrypointConfig $clientStoreEntrypointConfig;

	/**
	 * @generate-create-func
	 */
	public static function create(?ClientStoreEntrypointConfig $clientStoreEntrypointConfig) : self{
		$result = new self();
		$result->clientStoreEntrypointConfig = $clientStoreEntrypointConfig;
		return $result;
	}

	public function getClientStoreEntrypointConfig() : ?ClientStoreEntrypointConfig{ return $this->clientStoreEntrypointConfig; }

	public function decodePayload() : void{
		$this->clientStoreEntrypointConfig = $this->getOptional(ClientStoreEntrypointConfig::read(...));
	}

	public function encodePayload() : void{
		$this->putOptional($this->clientStoreEntrypointConfig, fn(ClientStoreEntrypointConfig $v) => $v->write($this));
	}

	public function mustBeDecoded() : bool{
		return false;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleServerStoreInfo($this);
	}
}
