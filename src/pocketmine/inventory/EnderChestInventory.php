<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\tile\EnderChest;

class EnderChestInventory extends ChestInventory
{
	public function __construct()
	{
		ContainerInventory::__construct(new Position(0, 0, 0));
	}

	public function getNetworkType() : int
	{
		return WindowTypes::CONTAINER;
	}

	public function getName() : string
	{
		return "EnderChest";
	}

	public function getDefaultSize() : int
	{
		return 27;
	}

	/**
	 * Set the holder's position to that of a tile
	 */
	public function setHolderPosition(EnderChest $enderChest) : void
	{
		$this->holder->x = $enderChest->getFloorX();
		$this->holder->y = $enderChest->getFloorY();
		$this->holder->z = $enderChest->getFloorZ();
		$this->holder->setLevel($enderChest->getLevel());
	}

	protected function getOpenSound() : int
	{
		return LevelSoundEventPacket::SOUND_ENDERCHEST_OPEN;
	}

	protected function getCloseSound() : int
	{
		return LevelSoundEventPacket::SOUND_ENDERCHEST_CLOSED;
	}

	/**
	 * This override is here for documentation and code completion purposes only.
	 * @return Position
	 */
	public function getHolder()
	{
		return $this->holder;
	}
}
