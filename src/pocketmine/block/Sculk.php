<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class Sculk extends Solid
{
	protected $id = self::SCULK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Sculk";
	}

	public function getHardness() : float
	{
		return 0.2;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_HOE;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function getXpDropAmount() : int{
		return 1;
	}
}
