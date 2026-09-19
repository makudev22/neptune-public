<?php


declare(strict_types=1);

namespace pocketmine\item;

/**
 * Items which are sometimes (but not always) consumable should implement this interface.
 *
 * Note: If implementing custom items, consider making separate items instead of using this.
 * This interface serves as a workaround for the consumability of buckets and shouldn't
 * really be used for anything else.
 */
interface MaybeConsumable extends Consumable
{
	public function canBeConsumed() : bool;
}
