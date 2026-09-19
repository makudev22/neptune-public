<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class ResourcePackChunkRequestPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_REQUEST_PACKET;

	public string $packId;
	public int $chunkIndex;

	protected function decodePayload() : void
	{
		$this->packId = $this->getString();
		$this->chunkIndex = $this->getLInt();
	}

	protected function encodePayload() : void
	{
		$this->putString($this->packId);
		$this->putLInt($this->chunkIndex);
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleResourcePackChunkRequest($this);
	}
}
