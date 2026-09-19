<?php


declare(strict_types=1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;

/**
 * @internal
 */
abstract class BaseBlockChangeEvent extends BlockEvent implements Cancellable{

	public function __construct(
		Block $block,
		private Block $newState
	){
		parent::__construct($block);
	}

	public function getNewState() : Block{
		return $this->newState;
	}
}
