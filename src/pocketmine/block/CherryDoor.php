<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryDoor extends WoodenDoor
{
	protected $id = self::CHERRY_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Door";
	}
}
