<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationHelper;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Log extends Solid
{
	public const OAK = 0;
	public const SPRUCE = 1;
	public const BIRCH = 2;
	public const JUNGLE = 3;

	public function getHardness() : float
	{
		return 2;
	}

	public function getName() : string
	{
		static $names = [
			self::OAK => "Oak Log",
			self::SPRUCE => "Spruce Log",
			self::BIRCH => "Birch Log",
			self::JUNGLE => "Jungle Log"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$this->meta = PillarRotationHelper::getMetaFromFace($this->meta, $face);
		return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function getVariantBitmask() : int
	{
		return 0x03;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getFuelTime() : int
	{
		return 300;
	}

	public function getFlameEncouragement() : int
	{
		return 5;
	}

	public function getFlammability() : int
	{
		return 5;
	}
}
