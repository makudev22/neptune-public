<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\TieredTool;

class Cobweb extends Flowable
{
	protected $id = self::COBWEB;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function hasEntityCollision() : bool
	{
		return true;
	}

	public function getName() : string
	{
		return "Cobweb";
	}

	public function getHardness() : float
	{
		return 4;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_SWORD | BlockToolType::TYPE_SHEARS;
	}

	public function getToolHarvestLevel() : int
	{
		return TieredTool::TIER_WOODEN;
	}

	public function onEntityCollide(Entity $entity) : void
	{
		$entity->resetFallDistance();
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [
			ItemFactory::get(Item::STRING)
		];
	}

	public function diffusesSkyLight() : bool
	{
		return true;
	}
}
