<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationHelper;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Deepslate extends Solid
{
	protected $id = self::DEEPSLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Deepslate";
	}

	public function getHardness() : float
	{
		return 3;
	}

	public function getBlastResistance() : float
	{
		return 30;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$this->meta = PillarRotationHelper::getMetaFromFace($this->meta, $face, true);
		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [ItemFactory::get(ItemIds::COBBLED_DEEPSLATE)];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}
}
