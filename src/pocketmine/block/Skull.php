<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Skull as TileSkull;
use pocketmine\tile\Tile;
use function floor;

class Skull extends Flowable
{
	protected $id = self::SKULL_BLOCK;

	protected $itemId = ItemIds::SKULL;

	/* List meta types head */
	public const int TYPE_SKELETON = 0;
	public const int TYPE_WITHER_SKELETON = 6;
	public const int TYPE_ZOMBIE = 12;
	public const int TYPE_PLAYER = 18;
	public const int TYPE_CREEPER = 24;
	public const int TYPE_DRAGON = 30;
	public const int TYPE_PIGLIN = 36;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 1;
	}

	public function getName() : string
	{
		static $names = [
			TileSkull::TYPE_SKELETON => "Skeleton Skull",
			TileSkull::TYPE_WITHER_SKELETON => "Wither Skeleton Skull",
			TileSkull::TYPE_ZOMBIE => "Zombie Head",
			TileSkull::TYPE_PLAYER => "Player Head",
			TileSkull::TYPE_CREEPER => "Creeper Head",
			TileSkull::TYPE_DRAGON => "Dragon Head",
			TileSkull::TYPE_PIGLIN => "Piglin Head"
		];

		return $names[$this->getVariant()] ?? "Mob Head";
	}

	protected function recalculateBoundingBox() : ?AxisAlignedBB
	{
		//TODO: different bounds depending on attached face (meta)
		return new AxisAlignedBB(
			$this->x + 0.25,
			$this->y,
			$this->z + 0.25,
			$this->x + 0.75,
			$this->y + 0.5,
			$this->z + 0.75
		);
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool
	{
		if ($face === Facing::DOWN) {
			return false;
		}

		$this->meta = $this->meta + ($face % 6);
		$this->getLevel()->setBlock($blockReplace, $this, true);
		Tile::createTile(Tile::SKULL, $this->getLevel(), TileSkull::createNBT($this, $face, $item, $player));

		return true;
	}

	public function getDropsForCompatibleTool(Item $item) : array
	{
		return [$this->asItem()];
	}

	public function isAffectedBySilkTouch() : bool
	{
		return false;
	}

	public function getPickedItem(bool $addUserData = false) : Item
	{
		return $this->asItem();
	}

	public function getVariant() : int{
		return (int) floor($this->meta / 6);
	}
}
