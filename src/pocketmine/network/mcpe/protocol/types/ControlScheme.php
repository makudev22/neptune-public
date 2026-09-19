<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\ClientboundControlSchemeSetPacket;

/**
 * @see ClientboundControlSchemeSetPacket
 */
enum ControlScheme : int
{
	use PacketIntEnumTrait;

	case LOCKED_PLAYER_RELATIVE_STRAFE = 0;
	case CAMERA_RELATIVE = 1;
	case CAMERA_RELATIVE_STRAFE = 2;
	case PLAYER_RELATIVE = 3;
	case PLAYER_RELATIVE_STRAFE = 4;
}
