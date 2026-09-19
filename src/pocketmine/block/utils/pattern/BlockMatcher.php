<?php


declare(strict_types=1);

namespace pocketmine\block\utils\pattern;

use pocketmine\block\Block;

class BlockMatcher{

	private int $blockId;

	private function __construct(int $blockId){
		$this->blockId = $blockId;
	}

	public static function forBlock(Block $block) : self{
		return new self($block->getId());
	}

	public static function forBlockId(int $blockId) : self{
		return new self($blockId);
	}

	public function apply(Block $block) : bool{
		return $block->getId() === $this->blockId;
	}
}
