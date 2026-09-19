<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\math\Vector3;

/**
 * Used by blocks which always have the same support requirements no matter what state they are in.
 * Prevents placement if support isn't available, and automatically destroys itself if support is removed.
 */
trait StaticSupportTrait{

	/**
	 * Implement this to define the block's support requirements.
	 */
	abstract protected function canBeSupportedAt(Block $block) : bool;

	/**
	 * @see Block::canBePlacedAt()
	 */
	public function canBePlacedAt(Block $blockReplace, Vector3 $clickVector, int $face, bool $isClickedBlock) : bool{
		return $this->canBeSupportedAt($blockReplace) && parent::canBePlacedAt($blockReplace, $clickVector, $face, $isClickedBlock);
	}

	/**
	 * @see Block::onNearbyBlockChange()
	 */
	public function onNearbyBlockChange() : void{
		if(!$this->canBeSupportedAt($this)){
			$this->level->useBreakOn($this);
		}else{
			parent::onNearbyBlockChange();
		}
	}
}
