<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\PresenceInfo;

/**
 * Relays server performance statistics to the client.
 * It's currently unclear what the purpose of this packet is - probably to power some fancy debug screen.
 */
class ServerPresenceInfoPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_PRESENCE_INFO_PACKET;

	private ?PresenceInfo $presenceConfig;

	/**
	 * @generate-create-func
	 */
	public static function create(?PresenceInfo $presenceConfig) : self{
		$result = new self();
		$result->presenceConfig = $presenceConfig;
		return $result;
	}

	public function getPresenceConfig() : ?PresenceInfo{ return $this->presenceConfig; }

	protected function decodePayload() : void
	{
		$this->presenceConfig = $this->getOptional(PresenceInfo::read(...));
	}

	protected function encodePayload() : void
	{
		$this->putOptional($this->presenceConfig, fn(PresenceInfo $v) => $v->write($this));
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerPresenceInfo($this);
	}
}
