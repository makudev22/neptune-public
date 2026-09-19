<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\item\Item;
use pocketmine\item\Shears;

class SilkTouchEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 15;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}

	public function canApplyTogether(Enchantment $enchantment) : bool
	{
		return parent::canApplyTogether($enchantment) && $enchantment->getId() !== Enchantment::FORTUNE;
	}

	public function canApply(Item $item) : bool
	{
		return $item instanceof Shears || parent::canApply($item);
	}
}
