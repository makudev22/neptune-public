<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;

class SculkSensor extends Solid
{
	protected $id = self::SCULK_SENSOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Sculk Sensor";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function getBlastResistance() : float
	{
		return 1.5;
	}

	public function getLightLevel() : int
	{
		return 1;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [];
	}
}
