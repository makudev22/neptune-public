<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\item\Item;
use pocketmine\level\generator\feature\FeatureFactory;
use pocketmine\level\generator\feature\FeaturePlaceContext;
use pocketmine\level\generator\feature\TreeFeatures;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Random;

class RedMushroom extends Flowable implements Growable
{

	protected $id = self::RED_MUSHROOM;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Red Mushroom";
	}

	public function ticksRandomly() : bool
	{
		return true;
	}

	public function onNearbyBlockChange() : void
	{
		if ($this->getSide(Facing::DOWN)->isTransparent()) {
			$this->getLevel()->useBreakOn($this);
		}
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$down = $this->getSide(Facing::DOWN);
		$lightLevel = $this->level->getFullLightAt($this->x, $this->y, $this->z);
		$downId = $down->getId();
		if(($lightLevel <= 12 && !$down->isTransparent()) || $down instanceof Mycelium || $down instanceof Podzol || $downId === BlockIds::CRIMSON_NYLIUM || $downId === BlockIds::WARPED_NYLIUM){
			$this->getLevel()->setBlock($blockReplace, $this, true, true);

			return true;
		}

		return false;
	}

	public function canGrow(Random $random, ?Player $player) : bool{
		return true;
	}

	public function canUseBonemeal(Random $random, ?Player $player) : bool{
		return $random->nextFloat() < 0.4;
	}

	public function grow(Random $random, ?Player $player) : void{
		$feature = FeatureFactory::getInstance()->get(TreeFeatures::HUGE_RED_MUSHROOM);
		if ($feature !== null) {
			if (BlockEventHelper::grow($this, BlockFactory::get(BlockIds::AIR), $player)) {
				$feature->place(new FeaturePlaceContext($this->level, $random, $this));
			}
		}
	}
}
