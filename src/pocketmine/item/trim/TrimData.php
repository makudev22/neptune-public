<?php


declare(strict_types=1);

namespace pocketmine\item\trim;

final readonly class TrimData {
	public function __construct(
		public ItemTrimPatternType $patternType,
		public ItemTrimMaterialType $materialType
	) {}
}
