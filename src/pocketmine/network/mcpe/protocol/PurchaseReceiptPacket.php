<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function count;

class PurchaseReceiptPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PURCHASE_RECEIPT_PACKET;

	/** @var string[] */
	public $entries = [];

	protected function decodePayload() : void
	{
		$count = $this->getUnsignedVarInt();
		for ($i = 0; $i < $count; ++$i) {
			$this->entries[] = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->entries));
		foreach ($this->entries as $entry) {
			$this->putString($entry);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handlePurchaseReceipt($this);
	}
}
