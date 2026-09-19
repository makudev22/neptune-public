<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class ReinforcedDeepslate extends Solid
{
	protected $id = self::REINFORCED_DEEPSLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Reinforced Deepslate";
	}

	public function getHardness() : float
	{
		return 55;
	}

	public function getBlastResistance() : float
	{
		return 6000;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}
}
