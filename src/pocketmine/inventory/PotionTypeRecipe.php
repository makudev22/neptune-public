<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;

class PotionTypeRecipe implements BrewingRecipe{

	private RecipeIngredient $input;
	private RecipeIngredient $ingredient;

	public function __construct(
		RecipeIngredient $input,
		RecipeIngredient $ingredient,
		private Item $output
	){
		if ($input instanceof Item) {
			$input = new ExactRecipeIngredient($input);
		}
		if ($ingredient instanceof Item) {
			$ingredient = new ExactRecipeIngredient($ingredient);
		}

		$this->ingredient = $ingredient;
		$this->input = $input;
		$this->output = clone $output;
	}

	public function getInput() : RecipeIngredient{
		return $this->input;
	}

	public function getIngredient() : RecipeIngredient{
		return $this->ingredient;
	}

	public function getOutput() : Item{
		return clone $this->output;
	}

	public function getResultFor(Item $input) : ?Item{
		return $this->input->accepts($input) ? $this->getOutput() : null;
	}
}
