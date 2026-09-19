<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\BlockEventHelper;
use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;
use function floor;

class Petals extends Flowable
{
	use StaticSupportTrait {
		canBePlacedAt as supportedWhenPlacedAt;
	}

	public const int MAX_COUNT = 3;

	public function getVariantBitmask() : int{
		return 0;
	}

	public function canBePlacedAt(Block $blockReplace, Vector3 $clickVector, int $face, bool $isClickedBlock) : bool{
		return ($blockReplace instanceof $this && $this->isSameType($blockReplace) && $blockReplace->getCount() < self::MAX_COUNT) || $this->supportedWhenPlacedAt($blockReplace, $clickVector, $face, $isClickedBlock);
	}

	protected function canBeSupportedAt(Block $block) : bool
	{
		return !$block->getSide(Facing::DOWN)->isTransparent();
	}

	public function onActivate(Item $item, Player $player = null) : bool
	{
		if($item instanceof Fertilizer){
			if($this->getCount() < self::MAX_COUNT){
				$grew = BlockEventHelper::grow($this, (clone $this)->setCount($this->getCount() + 1), $player);
			}else{
				$this->level->dropItem($this->add(0, 0.5, 0), $this->asItem());
				$grew = true;
			}
			if($grew){
				$item->pop();
				return true;
			}
		}
		return false;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if($blockReplace instanceof $this && $this->isSameType($blockReplace) && $blockReplace->getCount() < self::MAX_COUNT){
			$this->setCount($blockReplace->getCount() + 1);
			$this->setFace($blockReplace->getFace());
		}elseif($player !== null){
			$this->setFace($player->getDirection());
		}

		return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function getFlameEncouragement() : int{
		return 60;
	}

	public function getFlammability() : int{
		return 100;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [$this->asItem()->setCount($this->getCount() + 1)];
	}

	public function getCount() : int {
		return $this->meta & 0x03;
	}

	public function setCount(int $count) : self {
		$this->meta = ($this->meta & ~0x03) | ($count & 0x03);
		return $this;
	}

	public function getFace() : int {
		return (floor($this->meta / 4) - 1 + 4) % 4;
	}

	public function setFace(int $facing) : self {
		$this->meta = (4 * (($facing & 0x03) + 1) % 16) | $this->getCount();
		return $this;
	}
}
