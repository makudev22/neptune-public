<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ChunkCacheBlob;

use function count;

class ClientCacheMissResponsePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENT_CACHE_MISS_RESPONSE_PACKET;

	/** @var ChunkCacheBlob[] */
	private array $blobs = [];

	/**
	 * @param ChunkCacheBlob[] $blobs
	 */
	public static function create(array $blobs) : self
	{
		//type check
		(static function (ChunkCacheBlob ...$blobs) {})(...$blobs);

		$result = new self();
		$result->blobs = $blobs;
		return $result;
	}

	/**
	 * @return ChunkCacheBlob[]
	 */
	public function getBlobs() : array
	{
		return $this->blobs;
	}

	protected function decodePayload() : void
	{
		for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i) {
			$hash = $this->getLLong();
			$payload = $this->getString();
			$this->blobs[] = new ChunkCacheBlob($hash, $payload);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->blobs));
		foreach ($this->blobs as $blob) {
			$this->putLLong($blob->getHash());
			$this->putString($blob->getPayload());
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleClientCacheMissResponse($this);
	}
}
