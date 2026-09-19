<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;

class FurnaceRecipe{

	private RecipeIngredient $ingredient;

	public function __construct(
		private Item $result,
		RecipeIngredient|Item $ingredient
	){
		$this->result = clone $result;

		if ($ingredient instanceof Item) {
			$ingredient = new ExactRecipeIngredient($ingredient);
		}

		$this->ingredient = clone $ingredient;
	}

	public function getInput() : RecipeIngredient{
		return $this->ingredient;
	}

	public function getResult() : Item{
		return clone $this->result;
	}
}
