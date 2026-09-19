<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Living;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

abstract class Food extends Item implements FoodSource
{
	public function requiresHunger() : bool
	{
		return true;
	}

	/**
	 * @return Item
	 */
	public function getResidue()
	{
		return ItemFactory::get(Item::AIR, 0, 0);
	}

	public function getAdditionalEffects() : array
	{
		return [];
	}

	public function onConsume(Living $consumer)
	{

	}

	public function canStartUsingItem(Player $player) : bool
	{
		return !($player->getProtocolVersion() < ProtocolInfo::PROTOCOL_407 && $player->isCreative(true)) && (!$this->requiresHunger() || $player->canEat());
	}
}
