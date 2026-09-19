<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\math\Facing;

abstract class Eyeblossom extends Flowable
{
	use StaticSupportTrait;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	protected function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock instanceof Grass ||
			$supportBlock instanceof Dirt ||
			$supportBlock instanceof Mycelium ||
			$supportBlock instanceof Podzol ||
			$supportBlock instanceof Farmland ||
			$supportBlock instanceof PaleMoss;
	}
}
