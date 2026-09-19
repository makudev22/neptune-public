<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use InvalidArgumentException;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

use function count;

class SetScorePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SET_SCORE_PACKET;

	public const TYPE_CHANGE = 0;
	public const TYPE_REMOVE = 1;

	/** @var int */
	public $type;
	/** @var ScorePacketEntry[] */
	public $entries = [];

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i) {
				$entry = new ScorePacketEntry();
				$entry->type = $this->getUnsignedVarInt();
				$this->getString();
				$entry->scoreboardId = $this->getVarLong();
				switch ($entry->type) {
					case ScorePacketEntry::TYPE_REMOVE:
						if ($this->protocol === ProtocolInfo::PROTOCOL_2193) {
							$this->getBool();
						}
						$entry->objectiveName = $this->getBool() ? $this->getString() : "";
						break;
					case ScorePacketEntry::TYPE_PLAYER:
					case ScorePacketEntry::TYPE_ENTITY:
						$entry->objectiveName = $this->getString();
						$entry->score = $this->getLInt();
						$entry->entityUniqueId = $this->getEntityUniqueId();
						break;
					case ScorePacketEntry::TYPE_FAKE_PLAYER:
						$entry->objectiveName = $this->getString();
						$entry->score = $this->getLInt();
						$entry->customName = $this->getString();
						break;
					default:
						throw new PacketDecodeException("Unknown entry type $entry->type");
				}
				$this->entries[] = $entry;
			}
			return;
		}
		$this->type = $this->getByte();
		for ($i = 0, $i2 = $this->getUnsignedVarInt(); $i < $i2; ++$i) {
			$entry = new ScorePacketEntry();
			$entry->scoreboardId = $this->getVarLong();
			$entry->objectiveName = $this->getString();
			$entry->score = $this->getLInt();
			if ($this->type !== self::TYPE_REMOVE) {
				$entry->type = $this->getByte();
				switch ($entry->type) {
					case ScorePacketEntry::TYPE_PLAYER:
					case ScorePacketEntry::TYPE_ENTITY:
						$entry->entityUniqueId = $this->getEntityUniqueId();
						break;
					case ScorePacketEntry::TYPE_FAKE_PLAYER:
						$entry->customName = $this->getString();
						break;
					default:
						throw new PacketDecodeException("Unknown entry type $entry->type");
				}
			}
			$this->entries[] = $entry;
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putUnsignedVarInt(count($this->entries));
			foreach ($this->entries as $entry) {
				$entryType = $this->type === self::TYPE_REMOVE ? ScorePacketEntry::TYPE_REMOVE : $entry->type;
				$this->putUnsignedVarInt($entryType);
				$this->putString(match ($entryType) {
					ScorePacketEntry::TYPE_REMOVE => "remove",
					ScorePacketEntry::TYPE_PLAYER => "changeplayer",
					ScorePacketEntry::TYPE_ENTITY => "changeentity",
					ScorePacketEntry::TYPE_FAKE_PLAYER => "changefakeplayer",
					default => throw new InvalidArgumentException("Unknown entry type $entryType")
				});
				$this->putVarLong($entry->scoreboardId);
				switch ($entryType) {
					case ScorePacketEntry::TYPE_REMOVE:
						if ($this->protocol === ProtocolInfo::PROTOCOL_2193) {
							$this->putBool(true);
						}
						$this->putBool($entry->objectiveName !== "");
						if ($entry->objectiveName !== "") {
							$this->putString($entry->objectiveName);
						}
						break;
					case ScorePacketEntry::TYPE_PLAYER:
					case ScorePacketEntry::TYPE_ENTITY:
						$this->putString($entry->objectiveName);
						$this->putLInt($entry->score);
						$this->putEntityUniqueId($entry->entityUniqueId);
						break;
					case ScorePacketEntry::TYPE_FAKE_PLAYER:
						$this->putString($entry->objectiveName);
						$this->putLInt($entry->score);
						$this->putString($entry->customName);
						break;
				}
			}
			return;
		}
		$this->putByte($this->type);
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			$this->putVarLong($entry->scoreboardId);
			$this->putString($entry->objectiveName);
			$this->putLInt($entry->score);
			if ($this->type !== self::TYPE_REMOVE) {
				$this->putByte($entry->type);
				switch ($entry->type) {
					case ScorePacketEntry::TYPE_PLAYER:
					case ScorePacketEntry::TYPE_ENTITY:
						$this->putEntityUniqueId($entry->entityUniqueId);
						break;
					case ScorePacketEntry::TYPE_FAKE_PLAYER:
						$this->putString($entry->customName);
						break;
					default:
						throw new InvalidArgumentException("Unknown entry type $entry->type");
				}
			}
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleSetScore($this);
	}
}
