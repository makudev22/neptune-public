<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationHelper;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class HayBale extends Solid
{
	protected $id = self::HAY_BALE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Hay Bale";
	}

	public function getHardness() : float
	{
		return 0.5;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$this->meta = PillarRotationHelper::getMetaFromFace($this->meta, $face);
		$this->getLevel()->setBlock($blockReplace, $this, true, true);

		return true;
	}

	public function getVariantBitmask() : int
	{
		return 0x03;
	}

	public function getFlameEncouragement() : int
	{
		return 60;
	}

	public function getFlammability() : int
	{
		return 20;
	}
}
