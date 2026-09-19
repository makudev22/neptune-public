<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;

use function array_filter;

class StonePressurePlate extends PressurePlate
{
	public function getHardness() : float
	{
		return 0.5;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_PICKAXE;
	}

	protected function filterIrrelevantEntities(array $entities) : array
	{
		return array_filter($entities, fn (Entity $e) => $e instanceof Living); //TODO: armor stands should activate stone plates too
	}

	public function getDeactivationDelayTicks() : int {
		return 20;
	}
}
