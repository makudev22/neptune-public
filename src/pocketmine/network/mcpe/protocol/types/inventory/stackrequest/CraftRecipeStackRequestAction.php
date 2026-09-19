<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Tells that the current transaction crafted the specified recipe.
 */
final class CraftRecipeStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_RECIPE;

	final public function __construct(
		private int $recipeId,
		private int $repetitions
	) {
	}

	public function getRecipeId() : int
	{
		return $this->recipeId;
	}

	public function getRepetitions() : int
	{
		return $this->repetitions;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$recipeId = $in->readRecipeNetId();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$repetitions = $in->getByte();
		}
		return new self($recipeId, $repetitions ?? 1);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->writeRecipeNetId($this->recipeId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$out->putByte($this->repetitions);
		}
	}
}
