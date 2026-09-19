<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ChunkRadiusUpdatedPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CHUNK_RADIUS_UPDATED_PACKET;

	/** @var int */
	public $radius;

	protected function decodePayload() : void
	{
		$this->radius = $this->getVarInt();
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->radius);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleChunkRadiusUpdated($this);
	}
}
