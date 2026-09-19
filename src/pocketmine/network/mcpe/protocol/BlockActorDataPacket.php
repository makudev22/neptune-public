<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class BlockActorDataPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::BLOCK_ACTOR_DATA_PACKET;

	public int $x = 0;
	public int $y = 0;
	public int $z = 0;
	public string $namedtag;

	public static function create(int $x, int $y, int $z, string $namedtag) : self
	{
		$result = new self();
		$result->x = $x;
		$result->y = $y;
		$result->z = $z;
		$result->namedtag = $namedtag;
		return $result;
	}

	protected function decodePayload() : void
	{
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->namedtag = $this->getRemaining();
	}

	protected function encodePayload() : void
	{
		$this->putBlockPosition($this->x, $this->y, $this->z);
		$this->put($this->namedtag);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleBlockActorData($this);
	}
}
