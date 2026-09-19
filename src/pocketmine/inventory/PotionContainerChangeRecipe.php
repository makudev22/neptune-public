<?php


declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class PotionContainerChangeRecipe implements BrewingRecipe{

	private RecipeIngredient $ingredient;

	public function __construct(
		private int $inputItemId,
		RecipeIngredient|Item $ingredient,
		private int $outputItemId
	){
		if ($ingredient instanceof Item) {
			$ingredient = new ExactRecipeIngredient($ingredient);
		}

		$this->ingredient = $ingredient;
	}

	public function getInputItemId() : int{
		return $this->inputItemId;
	}

	public function getIngredient() : RecipeIngredient{
		return $this->ingredient;
	}

	public function getOutputItemId() : int{
		return $this->outputItemId;
	}

	public function getResultFor(Item $input) : ?Item{
		return $input->getId() === $this->getInputItemId() ? ItemFactory::get($this->getOutputItemId(), $input->getDamage()) : null;
	}
}
