<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\NetworkSession as PacketHandlerInterface;
use pocketmine\network\mcpe\protocol\types\inventory\InventoryTransactionChangedSlotsHack;
use pocketmine\network\mcpe\protocol\types\inventory\MismatchTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\NormalTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\ReleaseItemTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\TransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;

use function count;

class InventoryTransactionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::INVENTORY_TRANSACTION_PACKET;

	public const TYPE_NORMAL = 0;
	public const TYPE_MISMATCH = 1;
	public const TYPE_USE_ITEM = 2;
	public const TYPE_USE_ITEM_ON_ENTITY = 3;
	public const TYPE_RELEASE_ITEM = 4;

	public int $requestId = 0;
	/** @var null|InventoryTransactionChangedSlotsHack[] */
	public ?array $requestChangedSlots = null;
	public ?TransactionData $trData = null;

	protected function decodePayload() : void{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->requestId = $this->readLegacyItemStackRequestId();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
				$hasChangedSlots = $this->getBool();
			} else {
				$hasChangedSlots = $this->requestId !== 0;
			}

			$this->requestChangedSlots = [];
			if ($hasChangedSlots) {
				for ($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i) {
					$this->requestChangedSlots[] = InventoryTransactionChangedSlotsHack::read($this);
				}
			}
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$transactionType = $this->getUnsignedVarInt();
		} else {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_1001 && !$this->getBool()) {
				throw new PacketDecodeException("Dummy optional bool transactionType should always be 1");
			}
			$transactionType = $this->getUnsignedVarInt();
			if ($this->protocol >= ProtocolInfo::PROTOCOL_1001 && !$this->getBool()) {
				throw new PacketDecodeException("Dummy optional bool for trData should always be 1");
			}
		}

		$this->trData = match ($transactionType) {
			self::TYPE_NORMAL => new NormalTransactionData(),
			self::TYPE_MISMATCH => new MismatchTransactionData(),
			self::TYPE_USE_ITEM => new UseItemTransactionData(),
			self::TYPE_USE_ITEM_ON_ENTITY => new UseItemOnEntityTransactionData(),
			self::TYPE_RELEASE_ITEM => new ReleaseItemTransactionData(),
			default => throw new PacketDecodeException("Unknown transaction type $transactionType"),
		};

		$this->trData->decode($this, true);
	}

	protected function encodePayload() : void{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_407) {
			$this->writeLegacyItemStackRequestId($this->requestId);
			$hasChangedSlots = $this->requestId !== 0;
			if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
				$this->putBool($hasChangedSlots);
			}
			if ($hasChangedSlots) {
				$this->putUnsignedVarInt(count($this->requestChangedSlots));
				foreach ($this->requestChangedSlots as $changedSlots) {
					$changedSlots->write($this);
				}
			}
		}

		if ($this->trData === null) {
			throw new \LogicException("Transaction data must be set before encoding");
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_1001 && $this->protocol < ProtocolInfo::PROTOCOL_2193) {
			$this->putBool(true);
		}
		$this->putUnsignedVarInt($this->trData->getTypeId());
		if ($this->protocol < ProtocolInfo::PROTOCOL_2193) {
			$this->putBool(true);
		}
		$this->trData->encode($this, true);
	}

	public function handle(PacketHandlerInterface $session) : bool
	{
		return $session->handleInventoryTransaction($this);
	}
}
