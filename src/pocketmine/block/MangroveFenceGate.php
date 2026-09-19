<?php


declare(strict_types=1);

namespace pocketmine\block;

class MangroveFenceGate extends FenceGate
{
	protected $id = self::MANGROVE_FENCE_GATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Mangrove Fence Gate";
	}
}
