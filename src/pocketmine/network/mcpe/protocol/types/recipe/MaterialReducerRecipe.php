<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

final class MaterialReducerRecipe{
	/**
	 * @param MaterialReducerRecipeOutput[] $outputs
	 * @phpstan-param list<MaterialReducerRecipeOutput> $outputs
	 */
	public function __construct(
		private int $inputItemId,
		private int $inputItemMeta,
		private array $outputs
	){}

	public function getInputItemId() : int{ return $this->inputItemId; }

	public function getInputItemMeta() : int{ return $this->inputItemMeta; }

	/** @return MaterialReducerRecipeOutput[] */
	public function getOutputs() : array{ return $this->outputs; }
}
