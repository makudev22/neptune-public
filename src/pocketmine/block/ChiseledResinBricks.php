<?php


declare(strict_types=1);

namespace pocketmine\block;

class ChiseledResinBricks extends ResinBricks
{
	protected $id = self::CHISELED_RESIN_BRICKS;

	public function getName() : string
	{
		return "Chiseled Resin Bricks";
	}
}
