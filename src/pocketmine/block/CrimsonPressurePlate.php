<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonPressurePlate extends WoodenPressurePlate
{
	protected $id = self::CRIMSON_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Crimson Pressure Plate";
	}

	public function getFuelTime() : int
	{
		return 0;
	}
}
