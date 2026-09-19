<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

use function count;

class MismatchTransactionData extends TransactionData
{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_MISMATCH;

	protected function decodeData(NetworkBinaryStream $in, bool $legacyTransaction) : void
	{
		if (count($this->actions) > 0) {
			throw new PacketDecodeException("Mismatch transaction type should not have any actions associated with it, but got " . count($this->actions));
		}
	}

	protected function encodeData(NetworkBinaryStream $out, bool $legacyTransaction) : void
	{

	}

	public static function new() : self
	{
		return new self(); //no arguments
	}
}
