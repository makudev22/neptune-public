<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

use pocketmine\utils\UUID;

class CommandOriginData {
	public OriginDataType $type;
	public UUID $uuid;
	public string $requestId;
	public int $playerActorUniqueId;
}
