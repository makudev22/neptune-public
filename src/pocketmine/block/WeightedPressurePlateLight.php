<?php


declare(strict_types=1);

namespace pocketmine\block;

class WeightedPressurePlateLight extends WeightedPressurePlate
{
	protected $id = self::LIGHT_WEIGHTED_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Weighted Pressure Plate Light";
	}
}
