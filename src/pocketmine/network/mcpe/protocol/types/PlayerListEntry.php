<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\entity\Skin;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\utils\Color;
use pocketmine\utils\UUID;

class PlayerListEntry
{
	public int $type = PlayerListPacket::TYPE_ADD;
	public UUID $uuid;
	public int $entityUniqueId;
	public string $username;
	public ?Skin $skin = null;
	public string $xboxUserId = "";
	public string $platformChatId = "";
	public int $buildPlatform = DeviceOS::UNKNOWN;
	public bool $isTeacher = false;
	public bool $isHost = false;
	public bool $isSubClient = false;
	public ?Color $color = null;

	public static function createRemovalEntry(UUID $uuid) : PlayerListEntry
	{
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;

		return $entry;
	}

	public static function createAdditionEntry(
		UUID $uuid,
		int $entityUniqueId,
		string $username,
		?Skin $skin,
		string $xboxUserId = "",
		string $platformChatId = "",
		int $buildPlatform = -1,
		bool $isTeacher = false,
		bool $isHost = false,
		bool $isSubClient = false,
		Color $color = null
	) : PlayerListEntry {
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;
		$entry->entityUniqueId = $entityUniqueId;
		$entry->username = $username;
		$entry->skin = $skin;
		$entry->xboxUserId = $xboxUserId;
		$entry->platformChatId = $platformChatId;
		$entry->buildPlatform = $buildPlatform;
		$entry->isTeacher = $isTeacher;
		$entry->isHost = $isHost;
		$entry->isSubClient = $isSubClient;
		$entry->color = $color;

		return $entry;
	}
}
