<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use function mt_rand;

class FrostedIce extends Ice
{

	public const MAX_AGE = 3;

	protected $id = self::FROSTED_ICE;

	public function getName() : string
	{
		return "Frosted Ice";
	}

	public function getHardness() : float
	{
		return 2.5;
	}

	public function onNearbyBlockChange() : void{
		$this->level->scheduleDelayedBlockUpdate($this, mt_rand(20, 40));
	}

	public function onRandomTick() : void{
		$level = $this->level;
		if((!$this->checkAdjacentBlocks(4) || mt_rand(0, 2) === 0) &&
			$level->getHighestAdjacentFullLightAt($this->x, $this->y, $this->z) >= 12 - $this->meta){
			if($this->tryMelt()){
				foreach($this->getAllSides() as $block){
					if($block instanceof FrostedIce){
						$block->tryMelt();
					}
				}
			}
		}else{
			$level->scheduleDelayedBlockUpdate($this, mt_rand(20, 40));
		}
	}

	public function onScheduledUpdate() : void{
		$this->onRandomTick();
	}

	private function checkAdjacentBlocks(int $requirement) : bool{
		$found = 0;
		for($x = -1; $x <= 1; ++$x){
			for($z = -1; $z <= 1; ++$z){
				if($x === 0 && $z === 0){
					continue;
				}
				if(
					$this->level->getBlockAt($this->x + $x, $this->y, $this->z + $z) instanceof FrostedIce &&
					++$found >= $requirement
				){
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Updates the age of the ice, destroying it if appropriate.
	 *
	 * @return bool Whether the ice was destroyed.
	 */
	private function tryMelt() : bool{
		$level = $this->level;
		if($this->meta >= self::MAX_AGE){
			BlockEventHelper::melt($this, BlockFactory::get(BlockIds::WATER));
			return true;
		}

		$this->meta++;
		$level->setBlock($this, $this);
		$level->scheduleDelayedBlockUpdate($this, mt_rand(20, 40));
		return false;
	}
}
