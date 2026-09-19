<?php


declare(strict_types=1);

namespace pocketmine\block;

class DaylightSensor extends Transparent
{
	protected $id = self::DAYLIGHT_SENSOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Daylight Sensor";
	}

	public function getHardness() : float
	{
		return 0.2;
	}

	public function getFuelTime() : int
	{
		return 300;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}
}
