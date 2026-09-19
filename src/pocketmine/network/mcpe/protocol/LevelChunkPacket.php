<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ChunkPosition;

use function count;
use const PHP_INT_MAX;
use const UINT32_MAX;

class LevelChunkPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::LEVEL_CHUNK_PACKET;

	/**
	 * Client will request all subchunks as needed up to the top of the world
	 */
	private const CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT = UINT32_MAX;
	/**
	 * Client will request subchunks as needed up to the height written in the packet, and assume that anything above
	 * that height is air (wtf mojang ...)
	 */
	private const CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT = UINT32_MAX - 1;

	//this appears large enough for a world height of 1024 blocks - it may need to be increased in the future
	private const MAX_BLOB_HASHES = 64;

	private ChunkPosition $chunkPosition;
	/** @phpstan-var DimensionIds::* */
	private int $dimensionId;
	private int $subChunkCount;
	private bool $clientSubChunkRequestsEnabled;
	private ?int $clientRequestSubChunkLimit = null;
	/** @var int[]|null */
	private ?array $usedBlobHashes = null;
	private string $extraPayload;

	/**
	 * @generate-create-func
	 * @param int[] $usedBlobHashes
	 * @phpstan-param DimensionIds::* $dimensionId
	 */
	public static function create(ChunkPosition $chunkPosition, int $dimensionId, int $subChunkCount, bool $clientSubChunkRequestsEnabled, ?array $usedBlobHashes, string $extraPayload) : self{
		$result = new self();
		$result->chunkPosition = $chunkPosition;
		$result->dimensionId = $dimensionId;
		$result->subChunkCount = $subChunkCount;
		$result->clientSubChunkRequestsEnabled = $clientSubChunkRequestsEnabled;
		$result->clientRequestSubChunkLimit = $clientSubChunkRequestsEnabled ? $subChunkCount : null;
		$result->usedBlobHashes = $usedBlobHashes;
		$result->extraPayload = $extraPayload;
		return $result;
	}

	/**
	 * @deprecated
	 */
	public static function withoutCache(ChunkPosition $chunkPosition, int $dimensionId, int $subChunkCount, string $payload) : self
	{
		$result = new self();
		$result->chunkPosition = $chunkPosition;
		$result->dimensionId = $dimensionId;
		$result->subChunkCount = $subChunkCount;
		$result->extraPayload = $payload;

		$result->clientSubChunkRequestsEnabled = false;

		return $result;
	}

	/**
	 * @deprecated
	 */
	public static function withCache(ChunkPosition $chunkPosition, int $dimensionId, int $subChunkCount, array $usedBlobHashes, string $extraPayload) : self
	{
		(static function (int ...$hashes) {})(...$usedBlobHashes);
		$result = new self();
		$result->chunkPosition = $chunkPosition;
		$result->dimensionId = $dimensionId;
		$result->subChunkCount = $subChunkCount;
		$result->extraPayload = $extraPayload;

		$result->clientSubChunkRequestsEnabled = true;
		$result->usedBlobHashes = $usedBlobHashes;

		return $result;
	}

	public function getChunkPosition() : ChunkPosition{ return $this->chunkPosition; }

	public function getDimensionId() : int{ return $this->dimensionId; }

	public function getSubChunkCount() : int{
		return $this->subChunkCount;
	}

	public function isClientSubChunkRequestEnabled() : bool{
		return $this->protocol >= ProtocolInfo::PROTOCOL_2193 ? $this->clientRequestSubChunkLimit !== null : $this->clientSubChunkRequestsEnabled;
	}

	public function getClientRequestSubChunkLimit() : ?int{
		return $this->clientRequestSubChunkLimit;
	}

	public function isCacheEnabled() : bool{
		return $this->usedBlobHashes !== null;
	}

	/**
	 * @return int[]|null
	 */
	public function getUsedBlobHashes() : ?array{
		return $this->usedBlobHashes;
	}

	public function getExtraPayload() : string{
		return $this->extraPayload;
	}

	protected function decodePayload() : void
	{
		$this->chunkPosition = ChunkPosition::read($this);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
				$this->dimensionId = $this->getVarInt();
			}

			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->subChunkCount = $this->getUnsignedVarInt();
				$this->clientRequestSubChunkLimit = $this->getBool() ? $this->getVarInt() : null;
				$this->clientSubChunkRequestsEnabled = $this->clientRequestSubChunkLimit !== null;
				$cacheEnabled = $this->getBool();
				$this->usedBlobHashes = [];
				$count = $this->getUnsignedVarInt();
				if($count > self::MAX_BLOB_HASHES){
					throw new PacketDecodeException("Expected at most " . self::MAX_BLOB_HASHES . " blob hashes, got " . $count);
				}
				for($i = 0; $i < $count; ++$i){
					$this->usedBlobHashes[] = $this->getLLong();
				}
				if(!$cacheEnabled && $count !== 0){
					throw new PacketDecodeException("Chunk cache metadata was provided while the cache is disabled");
				}
				$this->extraPayload = $this->getString();
				return;
			}

			$subChunkCountButNotReally = $this->getUnsignedVarInt();
			if($subChunkCountButNotReally === self::CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT){
				$this->clientSubChunkRequestsEnabled = true;
				$this->subChunkCount = PHP_INT_MAX;
			}elseif($subChunkCountButNotReally === self::CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT){
				$this->clientSubChunkRequestsEnabled = true;
				$this->subChunkCount = $this->getLShort();
			}else{
				$this->clientSubChunkRequestsEnabled = false;
				$this->subChunkCount = $subChunkCountButNotReally;
			}

			$cacheEnabled = $this->getBool();
			if($cacheEnabled){
				$this->usedBlobHashes = [];
				$count = $this->getUnsignedVarInt();
				if($count > self::MAX_BLOB_HASHES){
					throw new PacketDecodeException("Expected at most " . self::MAX_BLOB_HASHES . " blob hashes, got " . $count);
				}
				for($i = 0; $i < $count; ++$i){
					$this->usedBlobHashes[] = $this->getLLong();
				}
			}
		}
		$this->extraPayload = $this->getString();
	}

	protected function encodePayload() : void
	{
		$this->chunkPosition->write($this);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
				$this->putVarInt($this->dimensionId);
			}

			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
				$this->putUnsignedVarInt($this->subChunkCount);
				$this->putBool($this->clientRequestSubChunkLimit !== null);
				if($this->clientRequestSubChunkLimit !== null){
					$this->putVarInt($this->clientRequestSubChunkLimit);
				}
				$this->putBool($this->usedBlobHashes !== null);
				$this->putUnsignedVarInt(count($this->usedBlobHashes ?? []));
				foreach($this->usedBlobHashes ?? [] as $hash){
					$this->putLLong($hash);
				}
				$this->putString($this->extraPayload);
				return;
			}

			if($this->clientSubChunkRequestsEnabled){
				if($this->subChunkCount === PHP_INT_MAX){
					$this->putUnsignedVarInt(self::CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT);
				}else{
					$this->putUnsignedVarInt(self::CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT);
					$this->putLShort($this->subChunkCount);
				}
			}else{
				$this->putUnsignedVarInt($this->subChunkCount);
			}

			$this->putBool($this->usedBlobHashes !== null);
			if($this->usedBlobHashes !== null){
				$this->putUnsignedVarInt(count($this->usedBlobHashes));
				foreach($this->usedBlobHashes as $hash){
					$this->putLLong($hash);
				}
			}
		}

		$this->putString($this->extraPayload);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleLevelChunk($this);
	}
}
