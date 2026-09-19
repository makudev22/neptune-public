<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class ClockPayloadType{
	public const SYNC_STATE = 0;
	public const INITIALIZE_REGISTRY = 1;
	public const ADD_TIME_MARKER = 2;
	public const REMOVE_TIME_MARKER = 3;
}
