<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class TickingAreasLoadStatusPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::TICKING_AREAS_LOAD_STATUS_PACKET;

	private bool $waitingForPreload;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $waitingForPreload) : self
	{
		$result = new self();
		$result->waitingForPreload = $waitingForPreload;
		return $result;
	}

	public function isWaitingForPreload() : bool
	{
		return $this->waitingForPreload;
	}

	protected function decodePayload() : void
	{
		$this->waitingForPreload = $this->getBool();
	}

	protected function encodePayload() : void
	{
		$this->putBool($this->waitingForPreload);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleTickingAreasLoadStatus($this);
	}
}
