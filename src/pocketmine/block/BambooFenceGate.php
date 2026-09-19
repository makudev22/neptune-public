<?php


declare(strict_types=1);

namespace pocketmine\block;

class BambooFenceGate extends FenceGate
{
	protected $id = self::BAMBOO_FENCE_GATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Bamboo Fence Gate";
	}
}
