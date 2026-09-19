<?php


declare(strict_types=1);

namespace pocketmine\item;

class WoodenTrapdoor extends ItemBlock
{
	public function getFuelTime() : int
	{
		return 300;
	}
}
