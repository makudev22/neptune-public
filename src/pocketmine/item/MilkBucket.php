<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Living;
use pocketmine\Player;

class MilkBucket extends Item implements MaybeConsumable
{
	public function getMaxStackSize() : int
	{
		return 1;
	}

	public function getResidue()
	{
		return ItemFactory::get(Item::BUCKET);
	}

	public function getAdditionalEffects() : array
	{
		return [];
	}

	public function canBeConsumed() : bool
	{
		return true;
	}

	public function onConsume(Living $consumer)
	{
		$consumer->removeAllEffects();
	}

	public function canStartUsingItem(Player $player) : bool
	{
		return true;
	}
}
