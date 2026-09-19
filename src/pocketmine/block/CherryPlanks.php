<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryPlanks extends Planks
{
	protected $id = self::CHERRY_PLANKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Planks";
	}
}
