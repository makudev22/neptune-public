<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\cache;

use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\ExactRecipeIngredient;
use pocketmine\inventory\FurnaceType;
use pocketmine\inventory\MetaWildcardRecipeIngredient;
use pocketmine\inventory\RecipeIngredient;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\inventory\ShapelessRecipeType;
use pocketmine\inventory\SmithingTransformRecipe;
use pocketmine\inventory\SmithingTrimRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\recipe\CraftingRecipeBlockName;
use pocketmine\network\mcpe\protocol\types\recipe\FurnaceRecipe as ProtocolFurnaceRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\FurnaceRecipeBlockName;
use pocketmine\network\mcpe\protocol\types\recipe\IntIdMetaItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\PotionContainerChangeRecipe as ProtocolPotionContainerChangeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\PotionTypeRecipe as ProtocolPotionTypeRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient as ProtocolRecipeIngredient;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeUnlockingRequirement;
use pocketmine\network\mcpe\protocol\types\recipe\ShapedRecipe as ProtocolShapedRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\ShapelessRecipe as ProtocolShapelessRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTransformRecipe as ProtocolSmithingTransformRecipe;
use pocketmine\network\mcpe\protocol\types\recipe\SmithingTrimRecipe as ProtocolSmithingTrimRecipe;
use pocketmine\timings\Timings;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Binary;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\UUID;
use function array_map;
use function spl_object_id;
use function str_repeat;

final class CraftingDataCache{
	use SingletonTrait;

	/**
	 * @var CraftingDataPacket[][]
	 * @phpstan-var array<int, CraftingDataPacket>
	 */
	private array $caches = [];

	/**
	 * The client doesn't like recipes with ID 0 (as of 1.21.100) and complains about them in the content log
	 * This doesn't actually affect the function of the recipe, but it is annoying, so this offset fixes it
	 */
	public const RECIPE_ID_OFFSET = 1;

	public function getCache(CraftingManager $manager, int $protocolVersion) : CraftingDataPacket{
		$id = spl_object_id($manager);
		if(!isset($this->caches[$id][$protocolVersion])){
			$this->caches[$id][$protocolVersion] = $this->buildCraftingDataCache($manager, $protocolVersion);
		}

		return $this->caches[$id][$protocolVersion];
	}

	public function clearCache(CraftingManager $manager) : void{
		unset($this->caches[spl_object_id($manager)]);
	}

	/**
	 * Rebuilds the cached CraftingDataPacket.
	 */
	private function buildCraftingDataCache(CraftingManager $manager, int $protocolVersion) : CraftingDataPacket{
		Timings::$craftingDataCacheRebuild->startTiming();

		$nullUUID = UUID::fromBinary(str_repeat("\x00", 16), 0);
		$converter = TypeConverter::getInstance();
		$recipesWithTypeIds = [];

		$noUnlockingRequirement = new RecipeUnlockingRequirement(null);
		foreach ($manager->getCraftingRecipeIndex($protocolVersion) as $index => $recipe) {
			//the client doesn't like recipes with an ID of 0, so we need to offset them
			$recipeNetId = $index + self::RECIPE_ID_OFFSET;
			if ($recipe instanceof ShapelessRecipe) {
				$typeTag = match ($recipe->getType()) {
					ShapelessRecipeType::CRAFTING => CraftingRecipeBlockName::CRAFTING_TABLE,
					ShapelessRecipeType::STONECUTTER => CraftingRecipeBlockName::STONECUTTER,
					ShapelessRecipeType::CARTOGRAPHY => CraftingRecipeBlockName::CARTOGRAPHY_TABLE,
					ShapelessRecipeType::SMITHING => CraftingRecipeBlockName::SMITHING_TABLE,
				};

				$recipesWithTypeIds[] = new ProtocolShapelessRecipe(
					CraftingDataPacket::ENTRY_SHAPELESS,
					self::recipeId($recipeNetId, $protocolVersion),
					array_map(fn(RecipeIngredient $ingredient) : ProtocolRecipeIngredient => $converter->coreRecipeIngredientToNet($ingredient, $protocolVersion), $recipe->getIngredientList()),
					array_map(fn(Item $result) : ItemStack => $converter->coreItemStackToNet($result, $protocolVersion), $recipe->getResults()),
					$nullUUID,
					$typeTag,
					50,
					$noUnlockingRequirement,
					$recipeNetId
				);
			} elseif ($recipe instanceof ShapedRecipe) {
				$inputs = [];

				for ($row = 0, $height = $recipe->getHeight(); $row < $height; ++$row) {
					for ($column = 0, $width = $recipe->getWidth(); $column < $width; ++$column) {
						$inputs[$row][$column] = $converter->coreRecipeIngredientToNet($recipe->getIngredient($column, $row), $protocolVersion);
					}
				}

				$recipesWithTypeIds[] = new ProtocolShapedRecipe(
					CraftingDataPacket::ENTRY_SHAPED,
					self::recipeId($recipeNetId, $protocolVersion),
					$inputs,
					array_map(fn(Item $result) : ItemStack => $converter->coreItemStackToNet($result, $protocolVersion), $recipe->getResults()),
					$nullUUID,
					CraftingRecipeBlockName::CRAFTING_TABLE,
					50,
					true,
					$noUnlockingRequirement,
					$recipeNetId,
				);
			} elseif ($recipe instanceof SmithingTransformRecipe) {
				$template = $converter->coreRecipeIngredientToNet($recipe->getTemplate(), $protocolVersion);
				$input = $converter->coreRecipeIngredientToNet($recipe->getInput(), $protocolVersion);
				$addition = $converter->coreRecipeIngredientToNet($recipe->getAddition(), $protocolVersion);
				$output = $converter->coreItemStackToNet($recipe->getOutput(), $protocolVersion);
				$recipesWithTypeIds[] = new ProtocolSmithingTransformRecipe(
					CraftingDataPacket::ENTRY_SMITHING_TRANSFORM,
					self::recipeId($recipeNetId, $protocolVersion),
					$template,
					$input,
					$addition,
					$output,
					CraftingRecipeBlockName::SMITHING_TABLE,
					$recipeNetId
				);
			} elseif ($recipe instanceof SmithingTrimRecipe) {
				$template = $converter->coreRecipeIngredientToNet($recipe->getTemplate(), $protocolVersion);
				$input = $converter->coreRecipeIngredientToNet($recipe->getInput(), $protocolVersion);
				$addition = $converter->coreRecipeIngredientToNet($recipe->getAddition(), $protocolVersion);
				$recipesWithTypeIds[] = new ProtocolSmithingTrimRecipe(
					CraftingDataPacket::ENTRY_SMITHING_TRIM,
					self::recipeId($recipeNetId, $protocolVersion),
					$template,
					$input,
					$addition,
					CraftingRecipeBlockName::SMITHING_TABLE,
					$recipeNetId
				);
			} else {
				//TODO: probably special recipe types
			}
		}

		foreach ($manager->getFurnaceRecipes($protocolVersion) as $furnaceTypeName => $recipes) {
			$typeTag = match ($furnaceTypeName) {
				FurnaceType::FURNACE->name() => FurnaceRecipeBlockName::FURNACE,
				FurnaceType::BLAST_FURNACE->name() => FurnaceRecipeBlockName::BLAST_FURNACE,
				FurnaceType::SMOKER->name() => FurnaceRecipeBlockName::SMOKER,
				FurnaceType::CAMPFIRE->name() => FurnaceRecipeBlockName::CAMPFIRE,
				FurnaceType::SOUL_CAMPFIRE->name() => FurnaceRecipeBlockName::SOUL_CAMPFIRE
			};

			foreach ($recipes as $recipe) {
				if ($protocolVersion >= ProtocolInfo::PROTOCOL_975) {
					$recipeNetId = ($recipeNetId ?? self::RECIPE_ID_OFFSET) + 1;
					$recipesWithTypeIds[] = new ProtocolShapelessRecipe(
						CraftingDataPacket::ENTRY_SHAPELESS,
						self::recipeId($recipeNetId, $protocolVersion),
						[$converter->coreRecipeIngredientToNet($recipe->getInput(), $protocolVersion)],
						[$converter->coreItemStackToNet($recipe->getResult(), $protocolVersion)],
						$nullUUID,
						$typeTag,
						50,
						$noUnlockingRequirement,
						$recipeNetId
					);
				} else {
					$input = $converter->coreRecipeIngredientToNet($recipe->getInput(), $protocolVersion)->getDescriptor();
					if (!$input instanceof IntIdMetaItemDescriptor) {
						throw new AssumptionFailedError();
					}
					$recipesWithTypeIds[] = new ProtocolFurnaceRecipe(
						CraftingDataPacket::ENTRY_FURNACE_DATA,
						$input->getId(),
						$input->getMeta(),
						$converter->coreItemStackToNet($recipe->getResult(), $protocolVersion),
						$typeTag
					);
				}
			}
		}

		$potionTypeRecipes = [];
		$potionContainerChangeRecipes = [];
		if ($protocolVersion >= ProtocolInfo::PROTOCOL_407) {
			foreach ($manager->getPotionTypeRecipes($protocolVersion) as $recipe) {
				if ($protocolVersion >= ProtocolInfo::PROTOCOL_2193) {
					$input = self::potionIngredientToNetworkId($recipe->getInput(), $protocolVersion);
					$ingredient = self::potionIngredientToNetworkId($recipe->getIngredient(), $protocolVersion);
				} else {
					$input = $converter->coreRecipeIngredientToNet($recipe->getInput(), $protocolVersion)->getDescriptor();
					$ingredient = $converter->coreRecipeIngredientToNet($recipe->getIngredient(), $protocolVersion)->getDescriptor();
					if(!$input instanceof IntIdMetaItemDescriptor || !$ingredient instanceof IntIdMetaItemDescriptor){
						throw new AssumptionFailedError();
					}
					$input = [$input->getId(), $input->getMeta()];
					$ingredient = [$ingredient->getId(), $ingredient->getMeta()];
				}
				$output = $converter->coreItemStackToNet($recipe->getOutput(), $protocolVersion);
				$potionTypeRecipes[] = new ProtocolPotionTypeRecipe(
					$input[0],
					$input[1],
					$ingredient[0],
					$ingredient[1],
					$output->getId(),
					$output->getMeta()
				);
			}

			if ($protocolVersion >= ProtocolInfo::PROTOCOL_419) {
				$itemTranslator = ItemTranslator::getInstance($protocolVersion);
				foreach ($manager->getPotionContainerChangeRecipes($protocolVersion) as $recipe) {
					if ($protocolVersion >= ProtocolInfo::PROTOCOL_2193) {
						$ingredient = self::potionIngredientToNetworkId($recipe->getIngredient(), $protocolVersion);
					} else {
						$ingredient = $converter->coreRecipeIngredientToNet($recipe->getIngredient(), $protocolVersion)->getDescriptor();
						if(!$ingredient instanceof IntIdMetaItemDescriptor){
							throw new AssumptionFailedError();
						}
						$ingredient = [$ingredient->getId(), $ingredient->getMeta()];
					}

					$input = $itemTranslator->toNetworkId($recipe->getInputItemId(), 0);
					$output = $itemTranslator->toNetworkId($recipe->getOutputItemId(), 0);
					$potionContainerChangeRecipes[] = new ProtocolPotionContainerChangeRecipe(
						$input[0],
						$ingredient[0],
						$output[0]
					);
				}
			} else {
				foreach ($manager->getPotionContainerChangeRecipes($protocolVersion) as $recipe) {
					$ingredient = $converter->coreRecipeIngredientToNet($recipe->getIngredient(), $protocolVersion)->getDescriptor();
					if(!$ingredient instanceof IntIdMetaItemDescriptor){
						throw new AssumptionFailedError();
					}

					$potionContainerChangeRecipes[] = new ProtocolPotionContainerChangeRecipe(
						$recipe->getInputItemId(),
						$ingredient->getId(),
						$recipe->getOutputItemId()
					);
				}
			}
		}

		Timings::$craftingDataCacheRebuild->stopTiming();
		return CraftingDataPacket::create($recipesWithTypeIds, $potionTypeRecipes, $potionContainerChangeRecipes, [], true);
	}

	private static function recipeId(int $recipeNetId, int $protocolVersion) : string
	{
		return Binary::writeInt($recipeNetId);
	}

	private static function potionIngredientToNetworkId(RecipeIngredient $ingredient, int $protocolVersion) : array
	{
		if ($ingredient instanceof ExactRecipeIngredient) {
			$item = $ingredient->getItem();
		} elseif ($ingredient instanceof MetaWildcardRecipeIngredient) {
			$item = ItemFactory::get($ingredient->getItemId(), 0);
		} else {
			throw new AssumptionFailedError();
		}

		$protocolItem = $item->getItemProtocol($protocolVersion) ?? $item;
		return ItemTranslator::getInstance($protocolVersion)->toNetworkId($protocolItem->getId(), $protocolItem->getDamage());
	}
}
