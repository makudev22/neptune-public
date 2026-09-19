<?php


declare(strict_types=1);

namespace pocketmine\block;

class TorchFlower extends Flower
{
	protected $id = self::TORCHFLOWER;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Torch Flower";
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
