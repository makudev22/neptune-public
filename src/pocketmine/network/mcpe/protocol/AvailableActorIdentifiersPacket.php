<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class AvailableActorIdentifiersPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::AVAILABLE_ACTOR_IDENTIFIERS_PACKET;

	public string $namedtag;

	/**
	 * @generate-create-func
	 */
	public static function create(string $namedtag) : self
	{
		$result = new self();
		$result->namedtag = $namedtag;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->namedtag = $this->getRemaining();
	}

	protected function encodePayload() : void
	{
		$this->put($this->namedtag);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAvailableActorIdentifiers($this);
	}
}
