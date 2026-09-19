<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

final class TransmittedMetaConvertor implements BlockConvertor {
	public function __construct(
		private int $targetLegacyId,
		private int $minimalProtocol
	) {}

	public function to(Block $block, int $protocolVersion) : ?Block {
		if ($protocolVersion < $this->minimalProtocol) {
			return BlockFactory::get($this->targetLegacyId, $block->getDamage());
		}
		return null;
	}
}
