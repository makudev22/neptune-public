<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ShowCreditsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SHOW_CREDITS_PACKET;

	public const STATUS_START_CREDITS = 0;
	public const STATUS_END_CREDITS = 1;

	/** @var int */
	public $playerEid;
	/** @var int */
	public $status;

	protected function decodePayload() : void
	{
		$this->playerEid = $this->getEntityRuntimeId();
		$this->status = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->playerEid);
		$this->putVarInt($this->status);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleShowCredits($this);
	}
}
