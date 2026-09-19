<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Record;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Jukebox as TileJukebox;
use pocketmine\tile\Tile;

class Jukebox extends Solid
{
	protected $id = self::JUKEBOX;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Jukebox";
	}

	public function getHardness() : float
	{
		return 2.0;
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		$this->getLevel()->setBlock($blockReplace, $this, true, true);

		Tile::createTile(Tile::JUKEBOX, $this->getLevel(), TileJukebox::createNBT($this, $face, $item, $player));

		return true;
	}

	public function onActivate(Item $item, Player $player = null) : bool
	{
		if ($player instanceof Player) {
			$jb = $this->getLevel()->getTile($this);
			if ($jb instanceof TileJukebox) {
				if ($jb->getRecordItem() == null) {
					if ($item instanceof Record) {
						$this->level->setBlock($this, $this);

						$jb->setRecordItem($item);
						$jb->playDisc($player);
						$player->getInventory()->removeItem($item);
					}
				} else {
					$jb->dropDisc();
				}
			}
		}

		$this->level->setBlock($this, $this);
		return true;
	}

	public function onBreak(Item $item, Player $player = null) : bool
	{
		$tile = $this->getLevel()->getTile($this);
		if ($tile instanceof TileJukebox) {
			$tile->dropDisc();
		}

		return parent::onBreak($item, $player);
	}
}
