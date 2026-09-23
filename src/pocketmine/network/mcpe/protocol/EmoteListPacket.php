<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\utils\UUID;

use function count;

class EmoteListPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::EMOTE_LIST_PACKET;
	private const MAX_EMOTES = 1024;

	public int $playerEntityRuntimeId;
	/** @var UUID[] */
	public array $emoteIds;

	/**
	 * @param UUID[] $emoteIds
	 */
	public static function create(int $playerEntityRuntimeId, array $emoteIds) : self
	{
		$result = new self();
		$result->playerEntityRuntimeId = $playerEntityRuntimeId;
		$result->emoteIds = $emoteIds;
		return $result;
	}

	public function getPlayerEntityRuntimeId() : int
	{
		return $this->playerEntityRuntimeId;
	}

	/** @return UUID[] */
	public function getEmoteIds() : array
	{
		return $this->emoteIds;
	}

	protected function decodePayload() : void
	{
		$this->playerEntityRuntimeId = $this->getEntityRuntimeId();
		$this->emoteIds = [];
		$len = $this->getUnsignedVarInt();
		if ($len > self::MAX_EMOTES) {
			throw new PacketDecodeException("Too many emotes: $len");
		}
		for ($i = 0; $i < $len; ++$i) {
			$this->emoteIds[] = $this->getUUID();
		}
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->playerEntityRuntimeId);
		$this->putUnsignedVarInt(count($this->emoteIds));
		foreach ($this->emoteIds as $emoteId) {
			$this->putUUID($emoteId);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleEmoteList($this);
	}
}
