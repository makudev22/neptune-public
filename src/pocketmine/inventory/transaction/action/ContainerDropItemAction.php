<?php


declare(strict_types=1);

namespace pocketmine\inventory\transaction\action;

use pocketmine\network\mcpe\cache\CreativeInventoryCache;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

/**
 * Represents an action causing a change in an inventory slot.
 */
class ContainerDropItemAction extends DropItemAction
{
	protected int $fails = 0;

	public function onPreExecute(Player $source) : bool
	{
		return true;
	}

	public function execute(Player $source) : void
	{
		if ($source->getCraftingGrid()->contains($this->targetItem)) {
			$source->getCraftingGrid()->removeItem($this->targetItem);
		} elseif ($source->isCreative()) {
			if (CreativeInventoryCache::getInstance()->getItemIndex($this->targetItem) === -1) {
				if (++$this->fails >= 5) {
					return;
				}

				$source->addInventoryTransactionActions($this);
				return;
			}
		} else {
			if (++$this->fails >= 5) {
				return;
			}

			$source->addInventoryTransactionActions($this);
			return;
		}

		if (!$source->dropItem($this->targetItem)) {
			$source->getCraftingGrid()->removeItem($this->targetItem);
			$source->getInventory()->addItem($this->targetItem);

			$source->getInventory()->sendHeldItem($source->getViewers());
			if ($source->getProtocolVersion() < ProtocolInfo::PROTOCOL_407) {
				$source->getInventory()->sendContents($source);
			} else {
				$source->getInventory()->sendHeldItem($source);
			}

			return;
		}

		$source->getInventory()->sendHeldItem($source);
		$source->getInventory()->sendHeldItem($source->getViewers());
	}
}
