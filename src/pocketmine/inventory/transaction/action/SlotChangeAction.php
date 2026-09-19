<?php


declare(strict_types=1);

namespace pocketmine\inventory\transaction\action;

use pocketmine\event\inventory\InventoryClickEvent;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

use function spl_object_hash;

/**
 * Represents an action causing a change in an inventory slot.
 */
class SlotChangeAction extends InventoryAction
{
        protected Inventory $inventory;
        protected int $inventorySlot;

        public function __construct(Inventory $inventory, int $inventorySlot, Item $sourceItem, Item $targetItem)
        {
                parent::__construct($sourceItem, $targetItem);
                $this->inventory = $inventory;
                $this->inventorySlot = $inventorySlot;
        }

        /**
         * Returns the inventory involved in this action.
         */
        public function getInventory() : Inventory
        {
                return $this->inventory;
        }

        /**
         * Returns the slot in the inventory which this action modified.
         */
        public function getSlot() : int
        {
                return $this->inventorySlot;
        }

        /**
         * Checks if the item in the inventory at the specified slot is the same as this action's source item.
         */
        public function isValid(Player $source) : bool
        {
                if (!$this->inventory->slotExists($this->inventorySlot)) {
                        return false;
                }

                $currentItem = $this->inventory->getItem($this->inventorySlot);

                // For old protocols (1.1.5 / 113, and other pre-440 versions), the
                // client may send item NBT in a slightly different format than what
                // the server stored — e.g. enchantment "id"/"lvl" tags as IntTag
                // instead of ShortTag, or tags in a different order. The strict
                // equalsExact() comparison would then fail and the transaction would
                // be rejected, causing items (especially enchanted armor) to
                // "disappear" when placed into chests or shulker boxes.
                //
                // For these old protocols we relax the comparison to id + damage +
                // count only, mirroring how the new inventory system already handles
                // this (see Player::handleNormalTransaction): "Raw extraData may not
                // match because of TAG_Compound key ordering differences, and
                // decoding it to compare is costly. Assume that we're in sync if
                // id+meta+count+runtimeId match."
                if ($source->getProtocolVersion() < ProtocolInfo::PROTOCOL_440) {
                        return $currentItem->getId() === $this->sourceItem->getId()
                                && $currentItem->getDamage() === $this->sourceItem->getDamage()
                                && $currentItem->getCount() === $this->sourceItem->getCount();
                }

                return $currentItem->equalsExact($this->sourceItem);
        }

        public function onPreExecute(Player $source) : bool
        {
                $ev = new InventoryClickEvent($this->inventory, $source, $this->inventorySlot, $this->sourceItem);
                $ev->call();
                return !$ev->isCancelled();
        }

        /**
         * Sets the item into the target inventory.
         */
        public function execute(Player $source) : void
        {
                if ($this->inventory->setItem($this->inventorySlot, $this->targetItem, false)) {
                        $viewers = $this->inventory->getViewers();
                        unset($viewers[spl_object_hash($source)]);
                        $this->inventory->sendSlot($this->inventorySlot, $viewers);
                } else {
                        $this->inventory->sendSlot($this->inventorySlot, $source);
                }
        }

        public function revert(Player $source) : void
        {
                $this->inventory->sendContents($source);
        }
}
