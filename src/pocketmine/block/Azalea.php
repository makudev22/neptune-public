<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\block\utils\TreeGrower;
use pocketmine\math\Facing;
use pocketmine\Player;
use pocketmine\utils\Random;

class Azalea extends Flowable implements Growable {
	use StaticSupportTrait;

	protected $id = self::AZALEA;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Azalea";
	}

	public function canGrow(Random $random, ?Player $player) : bool{
		return true;
	}

	public function canUseBonemeal(Random $random, ?Player $player) : bool{
		return $random->nextFloat() < 0.45;
	}

	public function grow(Random $random, ?Player $player) : void {
		if (BlockEventHelper::grow($this, $this, $player)) {
			TreeGrower::AZALEA()->growTree($this->getLevel(), $this, $this, $random);
		}
	}

	protected function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock instanceof Grass ||
			$supportBlock instanceof Dirt ||
			$supportBlock instanceof Mycelium ||
			$supportBlock instanceof Podzol ||
			$supportBlock instanceof Farmland ||
			$supportBlock instanceof Mud;
	}
}
