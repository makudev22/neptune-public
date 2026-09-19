<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession;

class SimulationTypePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SIMULATION_TYPE_PACKET;

	public const int GAME = 0;
	public const int EDITOR = 1;
	public const int TEST = 2;

	private int $type;

	public static function create(int $type) : self
	{
		$result = new self();
		$result->type = $type;
		return $result;
	}

	public function getType() : int
	{
		return $this->type;
	}

	protected function decodePayload() : void
	{
		$this->type = $this->getByte();
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->type);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSimulationType($this);
	}
}
