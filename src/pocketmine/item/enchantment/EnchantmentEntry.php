<?php


declare(strict_types=1);

namespace pocketmine\item\enchantment;

class EnchantmentEntry
{
	/** @var Enchantment[] */
	private $enchantments;
	/** @var int */
	private $cost;
	/** @var string */
	private $randomName;

	/**
	 * @param Enchantment[] $enchantments
	 */
	public function __construct(array $enchantments, int $cost, string $randomName)
	{
		$this->enchantments = $enchantments;
		$this->cost = $cost;
		$this->randomName = $randomName;
	}

	/**
	 * @return Enchantment[]
	 */
	public function getEnchantments() : array
	{
		return $this->enchantments;
	}

	public function getCost() : int
	{
		return $this->cost;
	}

	public function getRandomName() : string
	{
		return $this->randomName;
	}
}
