<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class RespirationEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 10 + ($level - 1) * 20;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}
}
