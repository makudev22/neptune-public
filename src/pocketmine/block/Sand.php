<?php


declare(strict_types=1);

namespace pocketmine\block;

class Sand extends Fallable {
	public const TYPE_NORMAL = 0;
	public const TYPE_RED = 1;

	protected $id = self::SAND;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() : float{
		return 0.5;
	}

	public function getToolType() : int{
		return BlockToolType::TYPE_SHOVEL;
	}

	public function getName() : string{
		if ($this->getVariant() === 0x01) {
			return "Red Sand";
		}

		return "Sand";
	}
}
