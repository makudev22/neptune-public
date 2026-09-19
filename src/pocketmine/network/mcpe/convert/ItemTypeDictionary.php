<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert;

use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use function array_key_exists;

final class ItemTypeDictionary
{
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $intToStringIdMap = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $stringToIntMap = [];

	/**
	 * @param ItemTypeEntry[] $itemTypes
	 */
	public function __construct(private array $itemTypes)
	{
		foreach ($this->itemTypes as $type) {
			$this->stringToIntMap[$type->getStringId()] = $type->getNumericId();
			$this->intToStringIdMap[$type->getNumericId()] = $type->getStringId();
		}
	}

	/**
	 * @return ItemTypeEntry[]
	 */
	public function getEntries() : array
	{
		return $this->itemTypes;
	}

	public function fromStringId(string $stringId) : int
	{
		if (!array_key_exists($stringId, $this->stringToIntMap)) {
			throw new \InvalidArgumentException("Unmapped string ID \"$stringId\"");
		}
		return $this->stringToIntMap[$stringId];
	}

	public function fromIntId(int $intId) : string
	{
		if (!array_key_exists($intId, $this->intToStringIdMap)) {
			throw new \InvalidArgumentException("Unmapped int ID $intId");
		}
		return $this->intToStringIdMap[$intId];
	}
}
