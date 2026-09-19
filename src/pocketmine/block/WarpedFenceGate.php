<?php


declare(strict_types=1);

namespace pocketmine\block;

class WarpedFenceGate extends FenceGate
{
	protected $id = self::WARPED_FENCE_GATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Warped Fence Gate";
	}

	public function getFuelTime() : int
	{
		return 0;
	}

	public function getFlameEncouragement() : int
	{
		return 0;
	}

	public function getFlammability() : int
	{
		return 0;
	}
}
