<?php


declare(strict_types=1);

namespace pocketmine\level\format\io\leveldb\upgrade;

use pocketmine\nbt\tag\NamedTag;

final class BlockStateUpgradeSchemaValueRemap
{
	public function __construct(
		public NamedTag $old,
		public NamedTag $new
	) {
	}
}
