<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangrovePressurePlate extends WoodenPressurePlate
{
	protected $id = self::MANGROVE_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Pressure Plate";
	}
}
