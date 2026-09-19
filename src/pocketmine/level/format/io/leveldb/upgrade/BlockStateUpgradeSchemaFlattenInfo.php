<?php


declare(strict_types=1);

namespace pocketmine\level\format\io\leveldb\upgrade;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use function ksort;
use const SORT_STRING;

final class BlockStateUpgradeSchemaFlattenInfo
{
	/**
	 * @param string[] $flattenedValueRemaps
	 * @phpstan-param array<string, string> $flattenedValueRemaps
	 * @phpstan-param ?class-string<ByteTag|IntTag|StringTag> $flattenedPropertyType
	 */
	public function __construct(
		public string $prefix,
		public string $flattenedProperty,
		public string $suffix,
		public array $flattenedValueRemaps,
		public ?string $flattenedPropertyType = null
	) {
		ksort($this->flattenedValueRemaps, SORT_STRING);
	}

	public function equals(self $that) : bool
	{
		return $this->prefix === $that->prefix &&
			$this->flattenedProperty === $that->flattenedProperty &&
			$this->suffix === $that->suffix &&
			$this->flattenedValueRemaps === $that->flattenedValueRemaps &&
			$this->flattenedPropertyType === $that->flattenedPropertyType;
	}
}
