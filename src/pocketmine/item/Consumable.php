<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Living;

/**
 * Interface implemented by objects that can be consumed by mobs.
 */
interface Consumable extends Releasable
{
	/**
	 * Returns the leftover that this Consumable produces when it is consumed. For Items, this is usually air, but could
	 * be an Item to add to a Player's inventory afterwards (such as a bowl).
	 *
	 * @return Item|Block|mixed
	 */
	public function getResidue();

	/**
	 * @return EffectInstance[]
	 */
	public function getAdditionalEffects() : array;

	/**
	 * Called when this Consumable is consumed by mob, after standard resulting effects have been applied.
	 *
	 * @return void
	 */
	public function onConsume(Living $consumer);
}
