<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BushTrait;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\Facing;

class DryGrass extends Flowable
{
	use BushTrait;
	use StaticSupportTrait;

	protected function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock instanceof Grass ||
			$supportBlock instanceof Mycelium ||
			$supportBlock instanceof Podzol ||
			$supportBlock instanceof Dirt ||
			$supportBlock instanceof DirtWithRoots ||
			$supportBlock instanceof Farmland ||
			$supportBlock instanceof HardenedClay ||
			$supportBlock instanceof Sand ||
			$supportBlock instanceof SuspiciousSand ||
			$supportBlock instanceof Mud ||
			$supportBlock instanceof Moss ||
			$supportBlock instanceof PaleMoss; //TODO: MUDDY_MANGROVE_ROOTS
	}
}
