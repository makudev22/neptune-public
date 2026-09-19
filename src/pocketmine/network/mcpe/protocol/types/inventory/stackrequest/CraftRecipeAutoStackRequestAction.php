<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\item\Item;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;
use function count;

/**
 * Tells that the current transaction crafted the specified recipe, using the recipe book. This is effectively the same
 * as the regular crafting result action.
 */
final class CraftRecipeAutoStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_RECIPE_AUTO;

	/**
	 * @param RecipeIngredient[] $ingredients
	 * @phpstan-param list<RecipeIngredient> $ingredients
	 */
	final public function __construct(
		private int $recipeId,
		private int $repetitions,
		private int $repetitions2,
		private array $ingredients
	) {
	}

	public function getRecipeId() : int
	{
		return $this->recipeId;
	}

	public function getRepetitions() : int
	{
		return $this->repetitions;
	}

	public function getRepetitions2() : int
	{
		return $this->repetitions2;
	}

	/**
	 * @return Item[]
	 * @phpstan-return list<Item>
	 */
	public function getIngredients() : array
	{
		return $this->ingredients;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$recipeId = $in->readRecipeNetId();
		$repetitions = $in->getByte();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$repetitions2 = $in->getByte(); //repetitions property is sent twice, mojang...
		}
		$ingredients = [];
		for ($i = 0, $count = $in->getByte(); $i < $count; ++$i) {
			$ingredients[] = $in->getStackRequestRecipeIngredient();
		}
		return new self($recipeId, $repetitions, $repetitions2 ?? 0, $ingredients);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->writeRecipeNetId($this->recipeId);
		$out->putByte($this->repetitions);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			$out->putByte($this->repetitions2);
		}
		$out->putByte(count($this->ingredients));
		foreach ($this->ingredients as $ingredient) {
			$out->putStackRequestRecipeIngredient($ingredient);
		}
	}
}
