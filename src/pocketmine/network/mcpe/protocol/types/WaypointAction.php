<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class WaypointAction{
	public const NONE = 0;
	public const ADD = 1;
	public const REMOVE = 2;
	public const UPDATE = 3;
}
