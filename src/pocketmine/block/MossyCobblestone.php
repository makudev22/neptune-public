<?php


declare(strict_types=1);

namespace pocketmine\block;

class MossyCobblestone extends Cobblestone
{
	protected $id = self::MOSSY_COBBLESTONE;

	public function getName() : string
	{
		return "Moss Stone";
	}
}
