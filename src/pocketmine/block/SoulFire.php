<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\math\Facing;

class SoulFire extends BaseFire
{
	protected $id = self::SOUL_FIRE;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Soul Fire Block";
	}

	public function getLightLevel() : int{
		return 10;
	}

	protected function getFireDamage() : int{
		return 2;
	}

	public static function canBeSupportedBy(Block $block) : bool{
		//TODO: this really ought to use some kind of tag system
		$id = $block->getId();
		return $id === BlockIds::SOUL_SAND || $id === BlockIds::SOUL_SOIL;
	}

	public function onNearbyBlockChange() : void{
		if(!self::canBeSupportedBy($this->getSide(Facing::DOWN))){
			$this->level->setBlock($this, BlockFactory::get(BlockIds::AIR));
		}
	}
}
