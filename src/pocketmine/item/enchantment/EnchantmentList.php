<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

use SplFixedArray;

class EnchantmentList
{
	/**
	 * @var SplFixedArray|EnchantmentEntry[]
	 * @phpstan-var SplFixedArray<EnchantmentEntry>
	 */
	private $enchantments;

	public function __construct(int $size)
	{
		$this->enchantments = new SplFixedArray($size);
	}

	public function setSlot(int $slot, EnchantmentEntry $entry) : void
	{
		$this->enchantments[$slot] = $entry;
	}

	public function getSlot(int $slot) : EnchantmentEntry
	{
		return $this->enchantments[$slot];
	}

	public function getSize() : int
	{
		return $this->enchantments->getSize();
	}
}
