<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function ord;

class RequestChunkRadiusPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::REQUEST_CHUNK_RADIUS_PACKET;

	/** @var int */
	public $radius;
	/** @var int */
	public $maxRadius;

	protected function decodePayload() : void
	{
		$this->radius = $this->getVarInt();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_582) {
			$this->maxRadius = ord($this->get(1));
		}
	}

	protected function encodePayload() : void
	{
		$this->putVarInt($this->radius);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_582) {
			$this->putByte($this->maxRadius);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleRequestChunkRadius($this);
	}
}
