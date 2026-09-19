<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveDoor extends WoodenDoor
{
	protected $id = self::MANGROVE_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Door";
	}
}
