<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;

/**
 * Recipe ingredient that matches items by their Minecraft ID only. This is used for things like the crafting table
 * recipe from planks (multiple types of planks are accepted).
 *
 * WARNING: Plugins shouldn't usually use this. This is a hack that relies on internal Minecraft behaviour, which might
 * change or break at any time.
 *
 * @internal
 */
final class MetaWildcardRecipeIngredient implements RecipeIngredient{

	public function __construct(
		private int $itemId,
	){}

	public function getItemId() : int{ return $this->itemId; }

	public function accepts(Item $item) : bool{
		if($item->getCount() < 1){
			return false;
		}

		return $item->getId() === $this->itemId;
	}

	public function __toString() : string{
		return "MetaWildcardRecipeIngredient($this->itemId)";
	}
}
