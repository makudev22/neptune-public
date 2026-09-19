<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;

/**
 * Called when a block spreads to another block, such as grass spreading to nearby dirt blocks.
 */
class BlockSpreadEvent extends BaseBlockChangeEvent{

	/**
	 * @param Block $block    Block being replaced (TODO: rename this)
	 * @param Block $source   Origin of the spread
	 * @param Block $newState Replacement block
	 */
	public function __construct(
		Block $block,
		private Block $source,
		Block $newState
	){
		parent::__construct($block, $newState);
	}

	public function getSource() : Block{
		return $this->source;
	}
}
