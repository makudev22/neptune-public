<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class NormalTransactionData extends TransactionData
{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_NORMAL;

	protected function decodeData(NetworkBinaryStream $in, bool $legacyTransaction) : void
	{

	}

	protected function encodeData(NetworkBinaryStream $out, bool $legacyTransaction) : void
	{

	}

	/**
	 * @param NetworkInventoryAction[] $actions
	 */
	public static function new(array $actions) : self
	{
		$result = new self();
		$result->actions = $actions;
		return $result;
	}
}
