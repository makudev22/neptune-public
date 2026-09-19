<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\DecoratedPot as TileDecoratedPot;
use pocketmine\tile\Tile;

class DecoratedPot extends Transparent
{
	protected $id = self::DECORATED_POT;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Decorated Pot";
	}

	public function getHardness() : float
	{
		return 0;
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{

		return new AxisAlignedBB(
			$this->x + 0.05,
			$this->y,
			$this->z + 0.05,
			$this->x + 0.95,
			$this->y,
			$this->z + 0.95
		);
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$faces = [
			0 => 1,
			1 => 2,
			2 => 3,
			3 => 0
		];

		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

		$this->getLevel()->setBlock($blockReplace, $this, true, true);

		Tile::createTile(Tile::DECORATED_POT, $this->getLevel(), TileDecoratedPot::createNBT($this, $face, $item, $player));

		return true;
	}
}
