<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ScoreboardIdentityPacketEntry;

use function count;

class SetScoreboardIdentityPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_SCOREBOARD_IDENTITY_PACKET;

	public const TYPE_REGISTER_IDENTITY = 0;
	public const TYPE_CLEAR_IDENTITY = 1;

	/** @var int */
	public $type;
	/** @var ScoreboardIdentityPacketEntry[] */
	public $entries = [];

	protected function decodePayload() : void
	{
		$this->type = $this->getByte();
		for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i) {
			$entry = new ScoreboardIdentityPacketEntry();
			$entry->scoreboardId = $this->getVarLong();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193 || $this->type === self::TYPE_REGISTER_IDENTITY) {
				$entry->entityUniqueId = $this->getEntityUniqueId();
			}

			$this->entries[] = $entry;
		}
	}

	protected function encodePayload() : void
	{
		$this->putByte($this->type);
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			$this->putVarLong($entry->scoreboardId);
			if ($this->protocol >= ProtocolInfo::PROTOCOL_2193 || $this->type === self::TYPE_REGISTER_IDENTITY) {
				$this->putEntityUniqueId($entry->entityUniqueId);
			}
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetScoreboardIdentity($this);
	}
}
