<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class UnbreakingEnchantment extends Enchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 5 + ($level - 1) * 8;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 50;
	}

	public function canApplyTogether(Enchantment $enchantment) : bool
	{
		return parent::canApplyTogether($enchantment) && $enchantment->getId() !== Enchantment::FORTUNE;
	}
}
