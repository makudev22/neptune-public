<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class BlueIce extends Transparent
{
	protected $id = self::BLUE_ICE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Blue Ice";
	}

	public function getHardness() : float
	{
		return 2.8;
	}

	public function getLightLevel() : int
	{
		return 1;
	}

	public function getFrictionFactor() : float
	{
		return 0.98;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function ticksRandomly() : bool
	{
		return true;
	}
}
