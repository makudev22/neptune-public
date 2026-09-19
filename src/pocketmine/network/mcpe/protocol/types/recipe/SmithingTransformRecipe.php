<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class SmithingTransformRecipe extends RecipeWithTypeId{

	public function __construct(
		int $typeId,
		private string $recipeId,
		private RecipeIngredient $template,
		private RecipeIngredient $input,
		private RecipeIngredient $addition,
		private ItemStack $output,
		private string $blockName,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{ return $this->recipeId; }

	public function getTemplate() : RecipeIngredient{ return $this->template; }

	public function getInput() : RecipeIngredient{ return $this->input; }

	public function getAddition() : RecipeIngredient{ return $this->addition; }

	public function getOutput() : ItemStack{ return $this->output; }

	public function getBlockName() : string{ return $this->blockName; }

	public function getRecipeNetId() : int{ return $this->recipeNetId; }

	public static function decode(int $typeId, NetworkBinaryStream $in) : self{
		$recipeId = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_582) {
			$template = $in->getRecipeIngredient();
		}

		$input = $in->getRecipeIngredient();
		$addition = $in->getRecipeIngredient();
		$output = $in->getItemStackWithoutStackId();
		$blockName = $in->getString();
		$recipeNetId = $in->readRecipeNetId();

		return new self(
			$typeId,
			$recipeId,
			$template ?? $input,
			$input,
			$addition,
			$output,
			$blockName,
			$recipeNetId
		);
	}

	public function encode(NetworkBinaryStream $out) : void{
		$out->putString($this->recipeId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_582) {
			$out->putRecipeIngredient($this->template);
		}

		$out->putRecipeIngredient($this->input);
		$out->putRecipeIngredient($this->addition);
		$out->putItemStackWithoutStackId($this->output);
		$out->putString($this->blockName);
		$out->writeRecipeNetId($this->recipeNetId);
	}
}
