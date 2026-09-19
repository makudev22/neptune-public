<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakDoor extends WoodenDoor
{
	protected $id = self::PALE_OAK_DOOR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Door";
	}
}
