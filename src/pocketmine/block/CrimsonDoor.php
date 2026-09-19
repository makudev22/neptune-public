<?php


declare(strict_types=1);

namespace pocketmine\block;

class CrimsonDoor extends WoodenDoor
{
	protected $id = self::CRIMSON_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Crimson Door";
	}

	public function getFuelTime() : int
	{
		return 0;
	}

	public function getFlameEncouragement() : int
	{
		return 0;
	}

	public function getFlammability() : int
	{
		return 0;
	}
}
