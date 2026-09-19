<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;

class SoulSandValleySurfaceBuilder extends ValleySurfaceBuilder {

	protected function getUnderBlocks() : array{
		return [
			BlockFactory::get(BlockIds::SOUL_SAND),
			BlockFactory::get(BlockIds::SOUL_SOIL)
		];
	}

	protected function getAboveBlocks() : array{
		return [
			BlockFactory::get(BlockIds::SOUL_SAND),
			BlockFactory::get(BlockIds::SOUL_SOIL)
		];
	}

	protected function getPatchBlock() : Block{
		return BlockFactory::get(BlockIds::GRAVEL);
	}
}
