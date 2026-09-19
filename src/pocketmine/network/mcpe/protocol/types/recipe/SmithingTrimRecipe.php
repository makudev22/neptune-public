<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class SmithingTrimRecipe extends RecipeWithTypeId{

	public function __construct(
		int $typeId,
		private string $recipeId,
		private RecipeIngredient $template,
		private RecipeIngredient $input,
		private RecipeIngredient $addition,
		private string $blockName,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{ return $this->recipeId; }

	public function getTemplate() : RecipeIngredient{ return $this->template; }

	public function getInput() : RecipeIngredient{ return $this->input; }

	public function getAddition() : RecipeIngredient{ return $this->addition; }

	public function getBlockName() : string{ return $this->blockName; }

	public function getRecipeNetId() : int{ return $this->recipeNetId; }

	public static function decode(int $typeId, NetworkBinaryStream $in) : self{
		$recipeId = $in->getString();
		$template = $in->getRecipeIngredient();
		$input = $in->getRecipeIngredient();
		$addition = $in->getRecipeIngredient();
		$blockName = $in->getString();
		$recipeNetId = $in->readRecipeNetId();

		return new self(
			$typeId,
			$recipeId,
			$template,
			$input,
			$addition,
			$blockName,
			$recipeNetId
		);
	}

	public function encode(NetworkBinaryStream $out) : void{
		$out->putString($this->recipeId);
		$out->putRecipeIngredient($this->template);
		$out->putRecipeIngredient($this->input);
		$out->putRecipeIngredient($this->addition);
		$out->putString($this->blockName);
		$out->writeRecipeNetId($this->recipeNetId);
	}
}
