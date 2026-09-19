<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\entity\Entity;
use pocketmine\entity\Smite;

class SmiteEnchantment extends DamageEnchantment
{
	public function getMinEnchantAbility(int $level) : int
	{
		return 5 + ($level - 1) * 8;
	}

	public function getMaxEnchantAbility(int $level) : int
	{
		return $this->getMinEnchantAbility($level) + 20;
	}

	public function isApplicableTo(Entity $victim) : bool
	{
		return $victim instanceof Smite;
	}

	public function getDamageBonus(int $enchantmentLevel) : float
	{
		return $enchantmentLevel * 2.5;
	}
}
