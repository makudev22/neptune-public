<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\item\Item;
use pocketmine\item\Shears;

class EfficiencyEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 1 + ($level - 1) * 10;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}

	public function canApply(Item $item) : bool
	{
		return $item instanceof Shears || parent::canApply($item);
	}
}
