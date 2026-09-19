<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class BindingEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 25;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return 50;
	}
}
