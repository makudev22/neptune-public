<?php


declare(strict_types=1);

namespace pocketmine\block;

class CherryFenceGate extends FenceGate
{
	protected $id = self::CHERRY_FENCE_GATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Cherry Fence Gate";
	}
}
