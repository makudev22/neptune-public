<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

enum PackSettingType : int{
	use PacketIntEnumTrait;

	case FLOAT = 0;
	case BOOL = 1;
	case STRING = 2;
}
