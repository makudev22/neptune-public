<?php


declare(strict_types=1);

namespace pocketmine\block;

class WeightedPressurePlateHeavy extends WeightedPressurePlate
{
	protected $id = self::HEAVY_WEIGHTED_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Weighted Pressure Plate Heavy";
	}
}
