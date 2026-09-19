<?php


declare(strict_types=1);

namespace pocketmine\block;

class Dandelion extends Flower
{
	protected $id = self::DANDELION;

	public function getName() : string
	{
		return "Dandelion";
	}

	public function getVariantBitmask() : int{
		return 0;
	}
}
