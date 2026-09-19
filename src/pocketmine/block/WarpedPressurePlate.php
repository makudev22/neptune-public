<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedPressurePlate extends WoodenPressurePlate
{
	protected $id = self::WARPED_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Pressure Plate";
	}

	public function getFuelTime() : int
	{
		return 0;
	}
}
