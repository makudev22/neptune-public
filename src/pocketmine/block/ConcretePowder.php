<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\math\Facing;

class ConcretePowder extends Fallable
{
	protected $id = self::CONCRETE_POWDER;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return ColorBlockMetaHelper::getColorFromMeta($this->getVariant()) . " Concrete Powder";
	}

	public function getHardness() : float{
		return 0.5;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function onNearbyBlockChange() : void{
		if(($water = $this->getAdjacentWater()) !== null){
			BlockEventHelper::form($this, BlockFactory::get(BlockIds::CONCRETE, $this->meta), $water);
		}else{
			parent::onNearbyBlockChange();
		}
	}

	public function tickFalling() : ?Block{
		if($this->getAdjacentWater() === null){
			return null;
		}
		return BlockFactory::get(BlockIds::CONCRETE, $this->meta);
	}

	private function getAdjacentWater() : ?Water{
		foreach(Facing::ALL as $i){
			if($i === Facing::DOWN){
				continue;
			}
			$block = $this->getSide($i);
			if($block instanceof Water){
				return $block;
			}
		}

		return null;
	}
}
