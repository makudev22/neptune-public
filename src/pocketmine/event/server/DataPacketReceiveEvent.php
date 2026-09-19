<?php


declare(strict_types=1);

namespace pocketmine\event\server;

use pocketmine\event\Cancellable;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

class DataPacketReceiveEvent extends ServerEvent implements Cancellable
{
	/** @var DataPacket */
	private $packet;
	/** @var Player */
	private $player;

	public function __construct(Player $player, DataPacket $packet)
	{
		$this->packet = $packet;
		$this->player = $player;
	}

	public function getPacket() : DataPacket
	{
		return $this->packet;
	}

	public function getPlayer() : Player
	{
		return $this->player;
	}
}
