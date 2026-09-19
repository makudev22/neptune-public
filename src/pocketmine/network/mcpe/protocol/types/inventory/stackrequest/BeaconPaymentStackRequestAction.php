<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Completes a transaction involving a beacon consuming input to produce effects.
 */
final class BeaconPaymentStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::BEACON_PAYMENT;

	public function __construct(
		private int $primaryEffectId,
		private int $secondaryEffectId
	) {
	}

	public function getPrimaryEffectId() : int
	{
		return $this->primaryEffectId;
	}

	public function getSecondaryEffectId() : int
	{
		return $this->secondaryEffectId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$primary = $in->getVarInt();
		$secondary = $in->getVarInt();
		return new self($primary, $secondary);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putVarInt($this->primaryEffectId);
		$out->putVarInt($this->secondaryEffectId);
	}
}
