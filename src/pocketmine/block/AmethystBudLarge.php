<?php


declare(strict_types=1);

namespace pocketmine\block;

class AmethystBudLarge extends AmethystBud
{
	protected $id = self::LARGE_AMETHYST_BUD;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Large Amethyst Bud";
	}

	public function getLightLevel() : int
	{
		return 4;
	}
}
