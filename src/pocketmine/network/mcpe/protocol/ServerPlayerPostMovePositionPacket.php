<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class ServerPlayerPostMovePositionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVER_PLAYER_POST_MOVE_POSITION_PACKET;

	private Vector3 $position;

	/**
	 * @generate-create-func
	 */
	public static function create(Vector3 $position) : self
	{
		$result = new self();
		$result->position = $position;
		return $result;
	}

	public function getPosition() : Vector3
	{
		return $this->position;
	}

	protected function decodePayload() : void
	{
		$this->position = $this->getVector3();
	}

	protected function encodePayload() : void
	{
		$this->putVector3($this->position);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerPlayerPostMovePosition($this);
	}
}
