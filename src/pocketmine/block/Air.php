<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;

/**
 * Air block
 */
class Air extends Transparent
{
	protected $id = self::AIR;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Air";
	}

	public function canPassThrough() : bool
	{
		return true;
	}

	public function isBreakable(Item $item) : bool
	{
		return false;
	}

	public function canBeFlowedInto() : bool
	{
		return true;
	}

	public function canBeReplaced() : bool
	{
		return true;
	}

	public function canBePlaced() : bool
	{
		return false;
	}

	public function isSolid() : bool
	{
		return false;
	}

	public function getBoundingBox() : ?AxisAlignedBB
	{
		return null;
	}

	public function getCollisionBoxes() : array
	{
		return [];
	}

	public function getHardness() : float
	{
		return -1;
	}

	public function getBlastResistance() : float
	{
		return 0;
	}
}
