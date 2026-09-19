<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class UpdateClientInputLocksPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_INPUT_LOCKS_PACKET;

	private int $flags;
	private Vector3 $position;

	/**
	 * @generate-create-func
	 */
	public static function create(int $flags, Vector3 $position) : self
	{
		$result = new self();
		$result->flags = $flags;
		$result->position = $position;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->flags = $this->getUnsignedVarInt();
		if ($this->protocol < ProtocolInfo::PROTOCOL_944) {
			$this->position = $this->getVector3();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt($this->flags);
		if ($this->protocol < ProtocolInfo::PROTOCOL_944) {
			$this->putVector3($this->position);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUpdateClientInputLocks($this);
	}
}
