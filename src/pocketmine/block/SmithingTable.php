<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\inventory\SmithingTableInventory;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class SmithingTable extends Solid
{
	protected $id = self::SMITHING_TABLE;

	public function __construct(int $meta = 0)
	{
		$this->meta = $meta;
	}

	public function getHardness() : float
	{
		return 2.5;
	}

	public function getName() : string
	{
		return "Smithing Table";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function onActivate(Item $item, Player $player = null) : bool{
		if ($player instanceof Player && $player->getProtocolVersion() >= ProtocolInfo::PROTOCOL_407) {
			$player->addWindow(new SmithingTableInventory($this));
		}

		return true;
	}

	public function getFuelTime() : int
	{
		return 300;
	}
}
