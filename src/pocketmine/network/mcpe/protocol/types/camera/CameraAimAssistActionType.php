<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum CameraAimAssistActionType : int
{
	use PacketIntEnumTrait;
	case SET = 0;
	case CLEAR = 1;
}
