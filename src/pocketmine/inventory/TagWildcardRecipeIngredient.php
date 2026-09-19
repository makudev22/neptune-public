<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\item\ItemTags;

/**
 * Recipe ingredient that matches items whose ID falls within a specific set. This is used for magic meta value
 * wildcards and also for ingredients which use item tags (since tags implicitly rely on ID only).
 *
 * @internal
 */
final class TagWildcardRecipeIngredient implements RecipeIngredient{

	public function __construct(
		private string $tagName
	){}

	public function getTagName() : string{ return $this->tagName; }

	public function accepts(Item $item) : bool{
		if($item->getCount() < 1){
			return false;
		}

		return ItemTags::getInstance()->tagContainsId($this->tagName, $item->getId(), $item->getDamage());
	}

	public function __toString() : string{
		return "TagWildcardRecipeIngredient($this->tagName)";
	}
}
