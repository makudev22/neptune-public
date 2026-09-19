<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class PowderSnow extends Transparent
{
	protected $id = self::POWDER_SNOW;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Powder Snow";
	}

	public function getHardness() : float
	{
		return 0.25;
	}

	public function getBlastResistance() : float
	{
		return 0.25;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}
}
