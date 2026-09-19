<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;

use function count;

final class InventoryTransactionChangedSlotsHack
{
	/**
	 * @param int[] $changedSlotIndexes
	 */
	public function __construct(
		private int $containerId,
		private array $changedSlotIndexes
	) {
	}

	public function getContainerId() : int
	{
		return $this->containerId;
	}

	/** @return int[] */
	public function getChangedSlotIndexes() : array
	{
		return $this->changedSlotIndexes;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$containerId = $in->getByte();
		$changedSlots = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			$changedSlots[] = $in->getByte();
		}
		return new self($containerId, $changedSlots);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->containerId);
		$out->putUnsignedVarInt(count($this->changedSlotIndexes));
		foreach ($this->changedSlotIndexes as $index) {
			$out->putByte($index);
		}
	}
}
