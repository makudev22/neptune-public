<?php


declare(strict_types=1);

namespace pocketmine\block;

class ActivatorRail extends RedstoneRail
{
	protected $id = self::ACTIVATOR_RAIL;

	public function getName() : string
	{
		return "Activator Rail";
	}

	//TODO
}
