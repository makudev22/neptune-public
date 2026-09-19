<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum CameraAimAssistPresetsPacketOperation : int{
	use PacketIntEnumTrait;

	case SET = 0;
	case ADD_TO_EXISTING = 1;
}
