<?php


declare(strict_types=1);


namespace pocketmine\inventory;

use pocketmine\block\SmithingTable;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\UIInventorySlotOffset;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\Player;

class SmithingTableInventory extends ContainerInventory implements FakeInventory, FakeResultInventory
{
	public const int SLOT_INPUT = 0;
	public const int SLOT_MATERIAL = 1;
	public const int SLOT_TEMPLATE = 2;

	public function __construct(SmithingTable $tile)
	{
		parent::__construct($tile);
	}

	public function getName() : string
	{
		return "Smithing Table";
	}

	public function getDefaultSize() : int
	{
		return 3;
	}

	public function getUIOffsets(?Player $player) : array
	{
		return UIInventorySlotOffset::SMITHING_TABLE;
	}

	public function onResult(Player $player, Item $result) : bool
	{
		return true; //TODO:
	}

	public function getNetworkType() : int
	{
		return WindowTypes::SMITHING_TABLE;
	}

	/**
	 * @param Player|Player[] $target
	 */
	public function sendContents($target) : void{
		if ($target instanceof Player) {
			$target = [$target];
		}

		foreach ($target as $player) {
			if ($player->getProtocolVersion() < ProtocolInfo::PROTOCOL_407) {
				continue;
			}
			parent::sendContents($player);
		}
	}

	/**
	 * @param Player|Player[] $target
	 */
	public function sendSlot(int $index, $target) : void
	{
		if ($target instanceof Player) {
			$target = [$target];
		}

		foreach ($target as $player) {
			if ($player->getProtocolVersion() < ProtocolInfo::PROTOCOL_407) {
				continue;
			}
			parent::sendSlot($index, $player);
		}
	}
}
