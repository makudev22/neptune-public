<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationHelper;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Quartz extends Solid
{
	public const NORMAL = 0;
	public const CHISELED = 1;
	public const PILLAR = 2;

	protected $id = self::QUARTZ_BLOCK;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0.8;
	}

	public function getName() : string
	{
		static $names = [
			self::NORMAL => "Quartz Block",
			self::CHISELED => "Chiseled Quartz Block",
			self::PILLAR => "Quartz Pillar"
		];
		return $names[$this->getVariant()] ?? "Unknown";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if ($this->getVariant() !== self::NORMAL) {
			$this->meta = PillarRotationHelper::getMetaFromFace($this->meta, $face);
		}
		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function getVariantBitmask() : int
	{
		return 0x03;
	}
}
