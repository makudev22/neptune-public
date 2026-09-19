<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\nbt\tag\CompoundTag;

final class ItemTypeEntry
{
	public function __construct(
		private string $stringId,
		private int $numericId,
		private bool $componentBased,
		private int $version,
		private CompoundTag $componentNbt
	) {
	}

	public function getStringId() : string
	{
		return $this->stringId;
	}

	public function getNumericId() : int
	{
		return $this->numericId;
	}

	public function isComponentBased() : bool
	{
		return $this->componentBased;
	}

	public function getVersion() : int
	{
		return $this->version;
	}

	public function getComponentNbt() : CompoundTag
	{
		return $this->componentNbt;
	}
}
