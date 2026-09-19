<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Apply a pattern to a banner using a loom.
 */
final class LoomStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_LOOM;

	public function __construct(
		private string $patternId
	) {
	}

	public function getPatternId() : string
	{
		return $this->patternId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		return new self($in->getString());
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->patternId);
	}
}
