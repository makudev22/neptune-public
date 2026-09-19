<?php


declare(strict_types=1);

namespace pocketmine\block;

class PaleOakFenceGate extends FenceGate
{
	protected $id = self::PALE_OAK_FENCE_GATE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Pale Oak Fence Gate";
	}
}
