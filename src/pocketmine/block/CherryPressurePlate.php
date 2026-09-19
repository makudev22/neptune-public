<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryPressurePlate extends WoodenPressurePlate
{
	protected $id = self::CHERRY_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Pressure Plate";
	}
}
