<?php


declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\IPlayer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;

/**
 * Called when a player's data is about to be saved to disk.
 */
class PlayerDataSaveEvent extends Event implements Cancellable
{
	/** @var CompoundTag */
	protected $data;
	/** @var string */
	protected $playerName;

	public function __construct(CompoundTag $nbt, string $playerName)
	{
		$this->data = $nbt;
		$this->playerName = $playerName;
	}

	/**
	 * Returns the data to be written to disk as a CompoundTag
	 */
	public function getSaveData() : CompoundTag
	{
		return $this->data;
	}

	public function setSaveData(CompoundTag $data) : void
	{
		$this->data = $data;
	}

	/**
	 * Returns the username of the player whose data is being saved. This is not necessarily an online player.
	 */
	public function getPlayerName() : string
	{
		return $this->playerName;
	}

	/**
	 * Returns the player whose data is being saved. This may be a Player or an OfflinePlayer.
	 * @return IPlayer (Player or OfflinePlayer)
	 */
	public function getPlayer() : IPlayer
	{
		return Server::getInstance()->getOfflinePlayer($this->playerName);
	}
}
