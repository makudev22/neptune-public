<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;

class BasaltDeltasSurfaceBuilder extends ValleySurfaceBuilder {

	protected function getUnderBlocks() : array{
		return [
			BlockFactory::get(BlockIds::BASALT),
			BlockFactory::get(BlockIds::BLACKSTONE)
		];
	}

	protected function getAboveBlocks() : array{
		return [
			BlockFactory::get(BlockIds::BASALT)
		];
	}

	protected function getPatchBlock() : Block{
		return BlockFactory::get(BlockIds::GRAVEL);
	}
}
