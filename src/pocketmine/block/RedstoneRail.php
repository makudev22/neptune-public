<?php


declare(strict_types=1);

namespace pocketmine\block;

class RedstoneRail extends BaseRail
{
	protected const FLAG_POWERED = 0x08;

	protected function getConnectionsForState() : array
	{
		return self::CONNECTIONS[$this->meta & ~self::FLAG_POWERED];
	}
}
