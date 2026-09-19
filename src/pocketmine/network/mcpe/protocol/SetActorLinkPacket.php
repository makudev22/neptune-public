<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\EntityLink;

class SetActorLinkPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_ACTOR_LINK_PACKET;

	/** @var EntityLink */
	public $link;

	protected function decodePayload() : void
	{
		$this->link = $this->getEntityLink();
	}

	protected function encodePayload() : void
	{
		$this->putEntityLink($this->link);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetActorLink($this);
	}
}
