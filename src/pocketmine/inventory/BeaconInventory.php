<?php


declare(strict_types=1);


namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\UIInventorySlotOffset;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\Player;
use pocketmine\tile\Beacon;

class BeaconInventory extends ContainerInventory implements FakeInventory, FakeResultInventory
{
	public function __construct(Beacon $tile)
	{
		parent::__construct($tile);
	}

	public function getName() : string
	{
		return "Beacon";
	}

	public function getDefaultSize() : int
	{
		return 1;
	}

	public function getUIOffsets(?Player $player) : array
	{
		return [
			UIInventorySlotOffset::BEACON_PAYMENT => 0
		];
	}

	public function onResult(Player $player, Item $result) : bool
	{
		return true; // TODO: check beacon
	}

	public function getNetworkType() : int
	{
		return WindowTypes::BEACON;
	}

	public function onClose(Player $who) : void
	{
		parent::onClose($who);

		$who->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(0));
		$this->clear(0);
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
