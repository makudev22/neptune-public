<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakPlanks extends Planks
{
	protected $id = self::PALE_OAK_PLANKS;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Planks";
	}
}
