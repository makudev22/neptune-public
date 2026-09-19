<?php


declare(strict_types=1);

namespace pocketmine\block;

class PolishedBlackstonePressurePlate extends StonePressurePlate
{
	protected $id = self::POLISHED_BLACKSTONE_PRESSURE_PLATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Polished Blackstone Pressure Plate";
	}
}
