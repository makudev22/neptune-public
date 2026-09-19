<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

class PotionContainerChangeRecipe{
	public function __construct(
		private int $inputItemId,
		private int $ingredientItemId,
		private int $outputItemId
	){}

	public function getInputItemId() : int{
		return $this->inputItemId;
	}

	public function getIngredientItemId() : int{
		return $this->ingredientItemId;
	}

	public function getOutputItemId() : int{
		return $this->outputItemId;
	}
}
