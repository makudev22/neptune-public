<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;

interface SmithingRecipe extends CraftingRecipe{

	public function getInput() : RecipeIngredient;

	public function getAddition() : RecipeIngredient;

	public function getTemplate() : RecipeIngredient;

	public function getResult(Item $template, Item $input, Item $addition) : ?Item;
}
