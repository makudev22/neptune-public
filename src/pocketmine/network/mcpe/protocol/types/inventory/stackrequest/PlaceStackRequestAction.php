<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Puts some (or all) of the items from the source slot into the destination slot.
 */
final class PlaceStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;
	use TakeOrPlaceStackRequestActionTrait;

	public const ID = ItemStackRequestActionType::PLACE;
}
