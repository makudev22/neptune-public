<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooPressurePlate extends WoodenPressurePlate
{
	protected $id = self::BAMBOO_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Pressure Plate";
	}
}
