<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ShowProfilePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SHOW_PROFILE_PACKET;

	/** @var string */
	public $xuid;

	protected function decodePayload() : void
	{
		$this->xuid = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->xuid);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleShowProfile($this);
	}
}
