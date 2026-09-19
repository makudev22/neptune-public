<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

/**
 * Relays server performance statistics to the client.
 * It's currently unclear what the purpose of this packet is - probably to power some fancy debug screen.
 */
class ServerStatsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_STATS_PACKET;

	private float $serverTime;
	private float $networkTime;

	/**
	 * @generate-create-func
	 */
	public static function create(float $serverTime, float $networkTime) : self
	{
		$result = new self();
		$result->serverTime = $serverTime;
		$result->networkTime = $networkTime;
		return $result;
	}

	public function getServerTime() : float
	{
		return $this->serverTime;
	}

	public function getNetworkTime() : float
	{
		return $this->networkTime;
	}

	protected function decodePayload() : void
	{
		$this->serverTime = $this->getLFloat();
		$this->networkTime = $this->getLFloat();
	}

	protected function encodePayload() : void
	{
		$this->putLFloat($this->serverTime);
		$this->putLFloat($this->networkTime);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerStats($this);
	}
}
