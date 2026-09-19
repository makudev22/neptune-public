<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class TickSyncPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::TICK_SYNC_PACKET;

	public int $clientSendTime;
	public int $serverReceiveTime;

	public static function request(int $clientTime) : self
	{
		$result = new self();
		$result->clientSendTime = $clientTime;
		$result->serverReceiveTime = 0; //useless
		return $result;
	}

	public static function response(int $clientSendTime, int $serverReceiveTime) : self
	{
		$result = new self();
		$result->clientSendTime = $clientSendTime;
		$result->serverReceiveTime = $serverReceiveTime;
		return $result;
	}

	public function getClientSendTime() : int
	{
		return $this->clientSendTime;
	}

	public function getServerReceiveTime() : int
	{
		return $this->serverReceiveTime;
	}

	protected function decodePayload() : void
	{
		$this->clientSendTime = $this->getLLong();
		$this->serverReceiveTime = $this->getLLong();
	}

	protected function encodePayload() : void
	{
		$this->putLLong($this->clientSendTime);
		$this->putLLong($this->serverReceiveTime);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleTickSync($this);
	}
}
