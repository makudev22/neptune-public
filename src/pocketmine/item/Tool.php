<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;

abstract class Tool extends Durable
{
	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getMiningEfficiency(Block $block) : float
	{
		$efficiency = 1;
		if (($block->getToolType() & $this->getBlockToolType()) !== 0) {
			$efficiency = $this->getBaseMiningEfficiency();
			if (($enchantmentLevel = $this->getEnchantmentLevel(Enchantment::EFFICIENCY)) > 0) {
				$efficiency += ($enchantmentLevel ** 2 + 1);
			}
		}

		return $efficiency;
	}

	protected function getBaseMiningEfficiency() : float
	{
		return 1;
	}
}
