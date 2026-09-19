<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum PredictedResult : int
{
	use PacketIntEnumTrait;

	case FAILURE = 0;
	case SUCCESS = 1;
}
