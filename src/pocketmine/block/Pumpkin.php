<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Pumpkin extends Solid
{
	protected $id = self::PUMPKIN;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 1;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function getName() : string
	{
		return "Pumpkin";
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if ($player instanceof Player) {
			$this->meta = ((int) $player->getDirection() + 1) % 4;
		}
		$this->getLevel()->setBlock($blockReplace, $this, true, true);

		return true;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
