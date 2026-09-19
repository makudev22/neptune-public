<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\entity\Entity;

class SharpnessEnchantment extends DamageEnchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 1 + ($level - 1) * 11;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 20;
	}

	public function isApplicableTo(Entity $victim) : bool
	{
		return true;
	}

	public function getDamageBonus(int $enchantmentLevel) : float
	{
		return 0.5 * ($enchantmentLevel + 1);
	}
}
