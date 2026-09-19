<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class LureEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 15 + ($level - 1) * 9;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}
}
