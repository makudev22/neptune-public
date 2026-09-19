<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\utils\Utils;

use function min;

abstract class Durable extends Item
{
	/**
	 * Returns whether this item will take damage when used.
	 */
	public function isUnbreakable() : bool
	{
		return $this->getNamedTag()->getByte("Unbreakable", 0) !== 0;
	}

	/**
	 * Sets whether the item will take damage when used.
	 *
	 * @return void
	 */
	public function setUnbreakable(bool $value = true)
	{
		$this->setNamedTagEntry(new ByteTag("Unbreakable", $value ? 1 : 0));
	}

	/**
	 * Applies damage to the item.
	 *
	 * @return bool if any damage was applied to the item
	 */
	public function applyDamage(int $amount) : bool
	{
		if ($this->isUnbreakable() || $this->isBroken()) {
			return false;
		}

		$amount -= $this->getUnbreakingDamageReduction($amount);

		$this->meta = min($this->meta + $amount, $this->getMaxDurability());
		if ($this->isBroken()) {
			$this->onBroken();
		}

		return true;
	}

	protected function getUnbreakingDamageReduction(int $amount) : int
	{
		if (($unbreakingLevel = $this->getEnchantmentLevel(Enchantment::UNBREAKING)) > 0) {
			$negated = 0;

			$chance = 1 / ($unbreakingLevel + 1);
			for ($i = 0; $i < $amount; ++$i) {
				if (Utils::getRandomFloat() > $chance) {
					$negated++;
				}
			}

			return $negated;
		}

		return 0;
	}

	/**
	 * Called when the item's damage exceeds its maximum durability.
	 */
	protected function onBroken() : void
	{
		$this->pop();
	}

	/**
	 * Returns the maximum amount of damage this item can take before it breaks.
	 */
	abstract public function getMaxDurability() : int;

	/**
	 * Returns whether the item is broken.
	 */
	public function isBroken() : bool
	{
		return $this->meta >= $this->getMaxDurability();
	}
}
