<?php


declare(strict_types=1);

namespace pocketmine\inventory\transaction;

use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

/**
 * This class facilitates generating SlotChangeActions to build an inventory transaction.
 * It wraps around the inventory you want to modify under transaction, and generates a diff of changes.
 * This allows you to use the normal Inventory API methods like addItem() and so on to build a transaction, without
 * modifying the original inventory.
 */
final class TransactionBuilderInventory extends BaseInventory
{
	/**
	 * @var \SplFixedArray|(Item|null)[]
	 * @phpstan-var \SplFixedArray<Item|null>
	 */
	private \SplFixedArray $changedSlots;

	public function __construct(
		private Inventory $actualInventory
	) {
		$this->changedSlots = new \SplFixedArray($this->actualInventory->getSize());
		parent::__construct();
		$this->changedSlots = new \SplFixedArray($this->actualInventory->getSize());
	}

	public function getActualInventory() : Inventory
	{
		return $this->actualInventory;
	}

	public function setContents(array $items, bool $send = true) : void
	{
		parent::setContents($items, $send);

		for ($i = 0, $size = $this->getSize(); $i < $size; ++$i) {
			if (!isset($items[$i])) {
				$this->clear($i);
			} else {
				$this->setItem($i, $items[$i]);
			}
		}
	}

	public function setItem(int $index, Item $item, bool $send = true) : bool
	{
		if (!$item->equalsExact($this->actualInventory->getItem($index))) {
			$this->changedSlots[$index] = $item->isNull() ? ItemFactory::air() : clone $item;
		}

		return parent::setItem($index, $item, $send);
	}

	public function getSize() : int
	{
		return $this->actualInventory->getSize();
	}

	public function getItem(int $index) : Item
	{
		return $this->changedSlots[$index] !== null ? clone $this->changedSlots[$index] : $this->actualInventory->getItem($index);
	}

	public function getContents(bool $includeEmpty = false) : array
	{
		$contents = $this->actualInventory->getContents($includeEmpty);
		foreach ($this->changedSlots as $index => $item) {
			if ($item !== null) {
				if ($includeEmpty || !$item->isNull()) {
					$contents[$index] = clone $item;
				} else {
					unset($contents[$index]);
				}
			}
		}
		return $contents;
	}

	/**
	 * @return SlotChangeAction[]
	 */
	public function generateActions() : array
	{
		$result = [];
		foreach ($this->changedSlots as $index => $newItem) {
			if ($newItem !== null) {
				$oldItem = $this->actualInventory->getItem($index);
				if (!$newItem->equalsExact($oldItem)) {
					$result[] = new SlotChangeAction($this->actualInventory, $index, $oldItem, $newItem);
				}
			}
		}
		return $result;
	}

	public function getName() : string
	{
		return "Transaction Builder Inventory";
	}

	public function getDefaultSize() : int
	{
		return $this->actualInventory->getSize();
	}
}
