<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Torch extends Flowable
{
	protected $id = self::TORCH;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getLightLevel() : int
	{
		return 14;
	}

	public function getName() : string
	{
		return "Torch";
	}

	public function onNearbyBlockChange() : void
	{
		$below = $this->getSide(Facing::DOWN);
		$meta = $this->getDamage();
		static $faces = [
			0 => Facing::DOWN,
			1 => Facing::WEST,
			2 => Facing::EAST,
			3 => Facing::NORTH,
			4 => Facing::SOUTH,
			5 => Facing::DOWN
		];
		$face = $faces[$meta] ?? Facing::DOWN;

		if ($this->getSide($face)->isTransparent() && !($face === Facing::DOWN && ($below->getId() === self::FENCE || $below->getId() === self::COBBLESTONE_WALL))) {
			$this->getLevel()->useBreakOn($this);
		}
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$below = $this->getSide(Facing::DOWN);

		if (!$blockClicked->isTransparent() && $face !== Facing::DOWN) {
			$faces = [
				Facing::UP => 5,
				Facing::NORTH => 4,
				Facing::SOUTH => 3,
				Facing::WEST => 2,
				Facing::EAST => 1
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($blockReplace, $this, true, true);

			return true;
		} elseif (!$below->isTransparent() || $below->getId() === self::FENCE || $below->getId() === self::COBBLESTONE_WALL) {
			$this->meta = 0;
			$this->getLevel()->setBlock($blockReplace, $this, true, true);

			return true;
		}

		return false;
	}

	public function getVariantBitmask() : int
	{
		return 0;
	}
}
