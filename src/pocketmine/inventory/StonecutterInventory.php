<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\block\Stonecutter;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\UIInventorySlotOffset;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\Player;

class StonecutterInventory extends ContainerInventory implements FakeInventory, FakeResultInventory
{
	public function __construct(Stonecutter $tile)
	{
		parent::__construct($tile);
	}

	public function getName() : string
	{
		return "Stonecutter";
	}

	public function getDefaultSize() : int
	{
		return 1;
	}

	public function getUIOffsets(?Player $player) : array
	{
		return [
			UIInventorySlotOffset::STONE_CUTTER_INPUT => 0
		];
	}

	public function onResult(Player $player, Item $result) : bool
	{
		return true;
	}

	public function getNetworkType() : int
	{
		return WindowTypes::STONECUTTER;
	}

	/**
	 * @param Player|Player[] $target
	 */
	public function sendContents($target) : void
	{
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
