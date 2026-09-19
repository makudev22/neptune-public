<?php


declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\inventory\CraftingGrid;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\Player;

class CraftingTable extends Solid
{
	protected $id = self::CRAFTING_TABLE;

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
		return "Crafting Table";
	}

	public function getToolType() : int
	{
		return BlockToolType::TYPE_AXE;
	}

	public function onActivate(Item $item, Player $player = null) : bool
	{
		if ($player instanceof Player) {
			$player->setCraftingGrid(new CraftingGrid($player, CraftingGrid::SIZE_BIG));

			if ($player->getProtocolVersion() >= ProtocolInfo::PROTOCOL_407) {
				$pk = new ContainerOpenPacket();
				$pk->windowId = $player->getNewWindowId();
				$pk->type = WindowTypes::WORKBENCH;
				$pk->x = $this->getFloorX();
				$pk->y = $this->getFloorY();
				$pk->z = $this->getFloorZ();
				$player->sendDataPacket($pk);

				$player->setCurrentWindowType(WindowTypes::WORKBENCH);
			}
		}

		return true;
	}

	public function getFuelTime() : int
	{
		return 300;
	}
}
