<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\PlayerLocationPacket;

/**
 * @see PlayerLocationPacket
 */
enum PlayerLocationType : int
{
	use PacketIntEnumTrait;

	case PLAYER_LOCATION_COORDINATES = 0;
	case PLAYER_LOCATION_HIDE = 1;
}
