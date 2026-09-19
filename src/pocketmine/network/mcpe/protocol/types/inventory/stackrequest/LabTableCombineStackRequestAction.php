<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Not clear what the point of this is. It's sent when the player uses a lab table, but it's not clear why this action
 * is needed.
 */
final class LabTableCombineStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::LAB_TABLE_COMBINE;

	public static function read(NetworkBinaryStream $in) : self
	{
		return new self();
	}

	public function write(NetworkBinaryStream $out) : void
	{
		//NOOP
	}
}
