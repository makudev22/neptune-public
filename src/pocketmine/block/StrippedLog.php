<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationHelper;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class StrippedLog extends Log{
	public function getName() : string{
		return $this->fallbackName;
	}

	public function getVariantBitmask() : int{
		return 0;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		$this->meta = PillarRotationHelper::getMetaFromFace($this->meta, $face, true);
		return Block::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function getFlameEncouragement() : int{
		if ($this->id === BlockIds::STRIPPED_CRIMSON_STEM || $this->id === BlockIds::STRIPPED_WARPED_STEM){
			return 0;
		}

		return parent::getFlameEncouragement();
	}

	public function getFlammability() : int{
		if ($this->id === BlockIds::STRIPPED_CRIMSON_STEM || $this->id === BlockIds::STRIPPED_WARPED_STEM){
			return 0;
		}

		return parent::getFlammability();
	}
}
