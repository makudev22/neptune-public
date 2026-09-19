<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class DriedGhast extends Solid
{
	protected $id = self::DRIED_GHAST;

	public const STAGE_0 = 0;
	public const STAGE_1 = 4;
	public const STAGE_2 = 8;
	public const STAGE_3 = 12;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 0;
	}

	public function getName() : string
	{
		return "Dried Ghast";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$faces = [
			0 => 1,
			1 => 2,
			2 => 3,
			3 => 0
		];
		$this->meta |= $player !== null ? $faces[$player->getDirection()] & 0x03 : 0;
		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
