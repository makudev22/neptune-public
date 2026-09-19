<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooDoor extends WoodenDoor
{
	protected $id = self::BAMBOO_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Door";
	}
}
