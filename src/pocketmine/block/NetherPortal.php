<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class NetherPortal extends Flowable
{
	protected $id = self::PORTAL;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getName() : string
	{
		return "Nether Portal";
	}

	public function getHardness() : float
	{
		return -1;
	}

	public function getBlastResistance() : float
	{
		return 0;
	}

	public function getLightLevel() : int
	{
		return 11;
	}

	public function isBreakable(Item $item) : bool
	{
		return false;
	}

	public function canBeFlowedInto() : bool
	{
		return false;
	}

	public function onBreak(Item $item, Player $player = null) : bool
	{
		$result = parent::onBreak($item, $player);

		foreach ($this->getHorizontalSides() as $side) {
			if ($side instanceof NetherPortal) {
				$side->onBreak($item, $player);
			}
		}

		return $result;
	}
}
