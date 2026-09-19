<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class MineBlockStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::MINE_BLOCK;

	public function __construct(
		private int $hotbarSlot,
		private int $predictedDurability,
		private int $stackId
	) {
	}

	public function getHotbarSlot() : int
	{
		return $this->hotbarSlot;
	}

	public function getPredictedDurability() : int
	{
		return $this->predictedDurability;
	}

	public function getStackId() : int
	{
		return $this->stackId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$hotbarSlot = $in->getVarInt();
		$predictedDurability = $in->getVarInt();
		$stackId = $in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 ? $in->getLInt() : $in->readItemStackNetIdVariant();
		return new self($hotbarSlot, $predictedDurability, $stackId);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putVarInt($this->hotbarSlot);
		$out->putVarInt($this->predictedDurability);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putLInt($this->stackId);
		} else {
			$out->writeItemStackNetIdVariant($this->stackId);
		}
	}
}
