<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class WaypointUpdateFlag{
	public const VISIBLE = 1 << 0;
	public const POSITION = 1 << 1;
	public const TEXTURE_ID = 1 << 2;
	public const COLOUR = 1 << 3;
	public const CLIENT_POSITION_AUTHORITY = 1 << 4;
	public const ACTOR_UNIQUE_ID = 1 << 5;
}
