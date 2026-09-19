<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\item\Axe;
use pocketmine\item\Item;

abstract class DamageEnchantment extends MeleeWeaponEnchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 15 + ($level - 1) * 9;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}

	public function canApplyTogether(Enchantment $enchantment) : bool
	{
		return !($enchantment instanceof DamageEnchantment);
	}

	public function canApply(Item $item) : bool
	{
		return $item instanceof Axe || parent::canApply($item);
	}
}
