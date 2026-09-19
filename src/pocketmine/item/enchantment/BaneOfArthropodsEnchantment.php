<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use pocketmine\entity\Arthropod;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;

class BaneOfArthropodsEnchantment extends MeleeWeaponEnchantment
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
		return $victim instanceof Arthropod;
	}

	public function getDamageBonus(int $enchantmentLevel) : float
	{
		return $enchantmentLevel * 2.5;
	}

	public function onPostAttack(Entity $attacker, Entity $victim, int $enchantmentLevel) : void
	{
		if ($victim instanceof Living) {
			$victim->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 + $victim->random->nextBoundedInt(10) * $enchantmentLevel, 3));
		}
	}
}
