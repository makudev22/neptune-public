<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function strlen;

class ResourcePackChunkDataPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_DATA_PACKET;

	public string $packId;
	public int $chunkIndex;
	public int $progress;
	public string $data;

	protected function decodePayload() : void
	{
		$this->packId = $this->getString();
		$this->chunkIndex = $this->getLInt();
		$this->progress = $this->getLLong();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->data = $this->getString();
		} else {
			$this->data = $this->get($this->getLInt());
		}
	}

	protected function encodePayload() : void
	{
		$this->putString($this->packId);
		$this->putLInt($this->chunkIndex);
		$this->putLLong($this->progress);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->putString($this->data);
		} else {
			$this->putLInt(strlen($this->data));
			$this->put($this->data);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleResourcePackChunkData($this);
	}
}
