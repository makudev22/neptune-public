<?php


declare(strict_types=1);

namespace pocketmine\block;

class AmethystBudSmall extends AmethystBud
{
	protected $id = self::SMALL_AMETHYST_BUD;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Small Amethyst Bud";
	}

	public function getLightLevel() : int
	{
		return 1;
	}
}
