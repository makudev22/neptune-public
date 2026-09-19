<?php


declare(strict_types=1);

namespace pocketmine\maps\renderer;

use pocketmine\maps\MapData;
use pocketmine\Player;

abstract class MapRenderer
{
	public function initialize(MapData $mapData) : void
	{

	}

	/**
	 * Renders a map
	 */
	abstract public function render(MapData $mapData, Player $player) : void;

	public function onMapCreated(Player $player, MapData $mapData) : void
	{

	}
}
