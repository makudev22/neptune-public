<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use pocketmine\Server;

class EndPortal extends Transparent
{
	protected $id = self::END_PORTAL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getLightLevel() : int
	{
		return 1;
	}

	public function getName() : string
	{
		return "End Portal";
	}

	public function getHardness() : float
	{
		return -1;
	}

	public function getBlastResistance() : float
	{
		return 18000000;
	}

	public function isBreakable(Item $item) : bool
	{
		return false;
	}

	public function hasEntityCollision() : bool
	{
		return true;
	}

	public function onEntityCollide(Entity $entity) : void
	{
		$server = Server::getInstance();
		if (!$server->isAllowTheEnd() || !$this->getBoundingBox()->isVectorInside($entity)) {
			return;
		}

		$currentLevel = $entity->getLevel();
		if ($currentLevel === null || $currentLevel->isClosed()) {
			return;
		}

		$targetDimension = $currentLevel->getDimension() === DimensionIds::THE_END ? DimensionIds::OVERWORLD : DimensionIds::THE_END;
		if ($targetDimension === DimensionIds::THE_END) {
			$targetLevel = $server->getTheEndLevel();
		} else {
			$targetLevel = $server->getDefaultLevel();
		}

		if ($targetLevel === null || $targetLevel->isClosed()) {
			return;
		}

		$entity->travelToDimension($targetDimension);
	}

	public function onBreak(Item $item, Player $player = null) : bool
	{
		$result = parent::onBreak($item, $player);

		foreach ($this->getHorizontalSides() as $side) {
			if ($side instanceof EndPortal) {
				$side->onBreak($item, $player);
			}
		}

		return $result;
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB{
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.75,
			$this->z + 1
		);
	}
}
