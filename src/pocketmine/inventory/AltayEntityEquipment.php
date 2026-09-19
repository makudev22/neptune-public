<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\entity\Living;
use pocketmine\inventory\utils\EquipmentSlot;
use pocketmine\item\Item;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\Player;

class AltayEntityEquipment extends BaseInventory
{
	/** @var Living */
	protected $holder;

	public function __construct(Living $entity)
	{
		$this->holder = $entity;
		parent::__construct();
	}

	public function getName() : string
	{
		return "Entity Equipment";
	}

	public function getDefaultSize() : int
	{
		return 2; // equipment slots (1 mainhand, 1 offhand)
	}

	public function getHolder() : Living
	{
		return $this->holder;
	}

	public function sendSlot(int $index, $target) : void
	{
		if ($target instanceof Player) {
			$target = [$target];
		}

		/** @var Player[][] $protocolPlayers */
		$protocolPlayers = [];
		foreach ($target as $player) {
			$protocolPlayers[$player->getProtocolVersion()][] = $player;
		}

		foreach ($protocolPlayers as $protocolVersion => $players) {
			$pk = MobEquipmentPacket::create(
				$this->holder->getId(),
				ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($this->getItem($index), $protocolVersion)),
				$index,
				$index,
				ContainerIds::INVENTORY
			);

			foreach ($players as $player) {
				$player->sendDataPacket($pk);
			}
		}
	}

	public function getViewers() : array
	{
		return $this->holder->getViewers();
	}

	public function getItemInHand() : Item
	{
		return $this->getItem(EquipmentSlot::MAINHAND);
	}

	public function getOffhandItem() : Item
	{
		return $this->getItem(EquipmentSlot::OFFHAND);
	}

	public function setItemInHand(Item $item, bool $send = true) : bool
	{
		return $this->setItem(EquipmentSlot::MAINHAND, $item, $send);
	}

	public function setOffhandItem(Item $item, bool $send = true) : bool
	{
		return $this->setItem(EquipmentSlot::OFFHAND, $item, $send);
	}

	public function sendContents($target) : void
	{
		$this->sendSlot(EquipmentSlot::MAINHAND, $target);
		$this->sendSlot(EquipmentSlot::OFFHAND, $target);
	}
}
