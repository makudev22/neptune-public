<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * I have no clear idea what this does. It seems to be the client hinting to the server "hey, put a secondary output in
 * X crafting grid slot". This is used for things like buckets.
 */
final class CraftingCreateSpecificResultStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_CREATE_SPECIFIC_RESULT;

	public function __construct(
		private int $resultIndex
	) {
	}

	public function getResultIndex() : int
	{
		return $this->resultIndex;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$slot = $in->getByte();
		return new self($slot);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->resultIndex);
	}
}
