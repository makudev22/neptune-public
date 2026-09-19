<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Item;

class Wool extends Solid
{
	protected $id = self::WOOL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.8;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SHEARS;
	}

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Wool";
	}

	public function getBreakTime(Item $item) : float
	{
		$time = parent::getBreakTime($item);
		if ($item->getBlockToolType() === BlockToolType::TYPE_SHEARS) {
			$time *= 3; //shears break compatible blocks 15x faster, but wool 5x
		}

		return $time;
	}

	public function getFlameEncouragement() : int
	{
		return 30;
	}

	public function getFlammability() : int
	{
		return 60;
	}
}
