<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\Player;

/**
 * Called when plants or crops grow.
 */
class BlockGrowEvent extends BaseBlockChangeEvent{

	public function __construct(
		Block $block,
		Block $newState,
		private ?Player $player = null,
	){
		parent::__construct($block, $newState);
	}

	/**
	 * It returns the player which grows the crop.
	 * It returns null when the crop grows by itself.
	 */
	public function getPlayer() : ?Player{
		return $this->player;
	}
}
