<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Slab;

final class NewSlabConvertor implements BlockConvertor {
	public function __construct(
		private int $targetLegacyId,
		private int $minimalProtocol
	) {}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($block instanceof Slab && $protocolVersion < $this->minimalProtocol) {
			return BlockFactory::get($this->targetLegacyId, ($block->isTop() ? 0x08 : 0x0));
		}

		return null;
	}
}
