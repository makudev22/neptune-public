<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\Facing;

class CactusFlower extends Flowable{
	use StaticSupportTrait;

	protected function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock instanceof Cactus ||
			$supportBlock instanceof Farmland ||
			$supportBlock->isSolid();
	}

	public function getFlameEncouragement() : int{
		return 60;
	}

	public function getFlammability() : int{
		return 100;
	}
}
