<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedDoor extends WoodenDoor
{
	protected $id = self::WARPED_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Door";
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
