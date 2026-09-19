<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum CameraAimAssistTargetMode : int
{
	use PacketIntEnumTrait;
	case ANGLE = 0;
	case DISTANCE = 1;
}
