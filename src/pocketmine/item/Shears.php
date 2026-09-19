<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;

class Shears extends Tool
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::SHEARS, $meta, "Shears");
	}

	public function getMaxDurability() : int
	{
		return 239;
	}

	public function getBlockToolType() : int
	{
		return BlockToolType::TYPE_SHEARS;
	}

	public function getBlockToolHarvestLevel() : int
	{
		return 1;
	}

	protected function getBaseMiningEfficiency() : float
	{
		return 15;
	}

	public function onDestroyBlock(Block $block) : bool
	{
		if ($block->getHardness() === 0.0 || $block->isCompatibleWithTool($this)) {
			return $this->applyDamage(1);
		}
		return false;
	}
}
