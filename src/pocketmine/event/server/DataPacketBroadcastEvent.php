<?php


declare(strict_types=1);

namespace pocketmine\event\server;

use pocketmine\event\Cancellable;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

class DataPacketBroadcastEvent extends ServerEvent implements Cancellable
{
	/** @var Player[] */
	private array $players;
	/** @var DataPacket[] */
	private array $packets;

	/**
	 * @param Player[]     $players
	 * @param DataPacket[] $packets
	 */
	public function __construct(array $players, array $packets)
	{
		$this->players = $players;
		$this->packets = $packets;
	}

	/**
	 * @return Player[]
	 */
	public function getPlayers() : array
	{
		return $this->players;
	}

	/**
	 * @param Player[] $players
	 */
	public function setPlayers(array $players) : void
	{
		$this->players = $players;
	}

	/**
	 * @return DataPacket[]
	 */
	public function getPackets() : array
	{
		return $this->packets;
	}

	/**
	 * @param DataPacket[] $packets
	 */
	public function setPackets(array $packets) : void
	{
		$this->packets = $packets;
	}
}
