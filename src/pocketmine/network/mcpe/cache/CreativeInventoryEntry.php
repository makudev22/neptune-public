<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\cache;

use pocketmine\item\Item;

final class CreativeInventoryEntry{
	private readonly Item $item;

	public function __construct(
		Item                            $item,
		private readonly int            $categoryId,
		private readonly ?CreativeGroup $group = null
	){
		$this->item = clone $item;
	}

	public function getItem() : Item{ return clone $this->item; }

	public function getCategoryId() : int{ return $this->categoryId; }

	public function getGroup() : ?CreativeGroup{ return $this->group; }

	public function matchesItem(Item $item) : bool{
		return $item->equals($this->item, checkDamage: true, checkCompound: false);
	}
}
