<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangrovePlanks extends Planks
{
	protected $id = self::MANGROVE_PLANKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Planks";
	}
}
