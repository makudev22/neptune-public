<?php


declare(strict_types=1);

namespace pocketmine\block;

class Element extends Solid {
	public function getHardness() : float
	{
		return 0;
	}
}
