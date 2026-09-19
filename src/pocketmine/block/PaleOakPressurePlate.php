<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakPressurePlate extends WoodenPressurePlate
{
	protected $id = self::PALE_OAK_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Pressure Plate";
	}
}
