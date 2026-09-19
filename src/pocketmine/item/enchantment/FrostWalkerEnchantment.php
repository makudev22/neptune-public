<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class FrostWalkerEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return $level * 10;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 15;
	}
}
