<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ClientCacheStatusPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENT_CACHE_STATUS_PACKET;

	public bool $enabled;

	public static function create(bool $enabled) : self
	{
		$result = new self();
		$result->enabled = $enabled;
		return $result;
	}

	public function isEnabled() : bool
	{
		return $this->enabled;
	}

	protected function decodePayload() : void
	{
		$this->enabled = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putBool($this->enabled);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleClientCacheStatus($this);
	}
}
