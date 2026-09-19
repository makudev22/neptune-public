<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\entity\Skin;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\skin\SerializedSkin;
use pocketmine\utils\Color;

use function count;

class PlayerListPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PLAYER_LIST_PACKET;

	public const TYPE_ADD = 0;
	public const TYPE_REMOVE = 1;

	/** @var PlayerListEntry[] */
	public $entries = [];
	/** @var int */
	public $type;

	public static function getPESkinId(Skin $skin) : string
	{
		$skinId = $skin->getSkinId();

		if (SerializedSkin::isSkinIdPE($skinId)) {
			return $skinId;
		}

		$type = match ($skin->getGeometryName()) {
			"geometry.humanoid.customSlim" => "CustomSlim",
			default => "Custom",
		};

		return "Standard_" . $type;
	}

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$count = $this->getUnsignedVarInt();
			for ($i = 0; $i < $count; ++$i) {
				$entry = new PlayerListEntry();
				$entry->type = $this->getUnsignedVarInt() === 1 ? self::TYPE_ADD : self::TYPE_REMOVE;
				$this->getByte();
				if ($entry->type === self::TYPE_ADD) {
					$entry->uuid = $this->getUUID();
					$entry->entityUniqueId = $this->getEntityUniqueId();
					$entry->username = $this->getString();
					$entry->xboxUserId = $this->getString();
					$entry->platformChatId = $this->getString();
					$entry->buildPlatform = $this->getLInt();
					$entry->skin = $this->getSkin();
					$entry->isTeacher = $this->getBool();
					$entry->isHost = $this->getBool();
					$entry->isSubClient = $this->getBool();
					$entry->color = Color::fromARGB($this->getLInt());
				} else {
					$entry->uuid = $this->getUUID();
				}
				$this->entries[$i] = $entry;
			}
			$this->type = $this->entries[0]->type ?? self::TYPE_ADD;
			return;
		}
		$this->type = $this->getByte();
		$count = $this->getUnsignedVarInt();
		for ($i = 0; $i < $count; ++$i) {
			$entry = new PlayerListEntry();

			if ($this->type === self::TYPE_ADD) {
				$entry->uuid = $this->getUUID();
				$entry->entityUniqueId = $this->getEntityUniqueId();
				$entry->username = $this->getString();
				if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
					$entry->xboxUserId = $this->getString();
					$entry->platformChatId = $this->getString();
					$entry->buildPlatform = $this->getLInt();
					$entry->skin = $this->getSkin();
					$entry->isTeacher = $this->getBool();
					$entry->isHost = $this->getBool();
					if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
						$entry->isSubClient = $this->getBool();
						if ($this->protocol >= ProtocolInfo::PROTOCOL_800) {
							$entry->color = Color::fromARGB($this->getLInt());
						}
					}
				} else {
					$entry->skin = new Skin($this->getString(), $this->getString());
				}
			} else {
				$entry->uuid = $this->getUUID();
			}

			$this->entries[$i] = $entry;
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($this->type === self::TYPE_ADD) {
				for ($i = 0; $i < $count; ++$i) {
					$this->getBool();
				}
			}
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->putUnsignedVarInt(count($this->entries));
			foreach ($this->entries as $entry) {
				$type = $this->type;
				$this->putUnsignedVarInt($type === self::TYPE_ADD ? 1 : 0);
				$this->putByte($type === self::TYPE_ADD ? 0 : 1);
				if ($type === self::TYPE_ADD) {
					$this->putUUID($entry->uuid);
					$this->putEntityUniqueId($entry->entityUniqueId);
					$this->putString($entry->username);
					$this->putString($entry->xboxUserId);
					$this->putString($entry->platformChatId);
					$this->putLInt($entry->buildPlatform);
					$this->putSkin($entry->skin ?? throw new \InvalidArgumentException("Skin must be set for an added player"));
					$this->putBool($entry->isTeacher);
					$this->putBool($entry->isHost);
					$this->putBool($entry->isSubClient);
					$this->putLInt(($entry->color ?? new Color(255, 255, 255))->toARGB());
				} else {
					$this->putUUID($entry->uuid);
				}
			}
			return;
		}
		$this->putByte($this->type);
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			if ($this->type === self::TYPE_ADD) {
				$this->putUUID($entry->uuid);
				$this->putEntityUniqueId($entry->entityUniqueId);
				$this->putString($entry->username);
				if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
					$this->putString($entry->xboxUserId);
					$this->putString($entry->platformChatId);
					$this->putLInt($entry->buildPlatform);
					$this->putSkin($entry->skin);
					$this->putBool($entry->isTeacher);
					$this->putBool($entry->isHost);
					if ($this->protocol >= ProtocolInfo::PROTOCOL_649) {
						$this->putBool($entry->isSubClient);
						if ($this->protocol >= ProtocolInfo::PROTOCOL_800) {
							$this->putLInt(($entry->color ?? new Color(255, 255, 255))->toARGB());
						}
					}
				} else {
					$this->putString(self::getPESkinId($entry->skin));
					$this->putString($entry->skin->getClientFriendlySkinData($this->protocol));
				}
			} else {
				$this->putUUID($entry->uuid);
			}
		}
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			if ($this->type === self::TYPE_ADD) {
				foreach ($this->entries as $entry) {
					$this->putBool($entry->skin->getSerializedSkin()->isTrustedSkin());
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
		return $session->handlePlayerList($this);
	}
}
