<?php


declare(strict_types=1);

namespace pocketmine\item;

class WoodenPressurePlate extends ItemBlock
{
	public function getFuelTime() : int
	{
		return 300;
	}

}
