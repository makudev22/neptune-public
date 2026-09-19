<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\level\generator\feature\FeatureFactory;
use pocketmine\level\generator\feature\FeaturePlaceContext;
use pocketmine\level\generator\feature\TreeFeatures;
use pocketmine\Player;
use pocketmine\utils\Random;

class BrownMushroom extends RedMushroom
{
	protected $id = self::BROWN_MUSHROOM;

	public function getName() : string
	{
		return "Brown Mushroom";
	}

	public function getLightLevel() : int
	{
		return 1;
	}

	public function grow(Random $random, ?Player $player) : void{
		$feature = FeatureFactory::getInstance()->get(TreeFeatures::HUGE_BROWN_MUSHROOM);
		if ($feature !== null && BlockEventHelper::grow($this, BlockFactory::get(BlockIds::AIR), $player)) {
			$feature->place(new FeaturePlaceContext($this->level, $random, $this));
		}
	}
}
