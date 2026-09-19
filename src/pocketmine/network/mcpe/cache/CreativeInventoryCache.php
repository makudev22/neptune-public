<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\cache;

use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\LegacyItemIdToStringIdMap;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\CreativeContentPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeGroupEntry;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeItemEntry;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\Player;
use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;
use function array_diff;
use function base64_decode;
use function file_exists;
use function hex2bin;
use function json_decode;
use function krsort;
use function scandir;
use function sort;
use function spl_object_id;
use const PHP_INT_MIN;
use const pocketmine\BEDROCK_DATA_PATH;

final class CreativeInventoryCache
{
	use SingletonTrait;

	private static function make() : self
	{
		/** @var CreativeInventoryEntry[][] $creativeInventoryEntries */
		$creativeInventoryEntries = [];

		$itemDeserializerFunc = function (array $data) : Item {
			$nbt = null;
			//Backwards compatibility
			if (isset($data["nbt"])) {
				$nbt = $data["nbt"];
			} elseif (isset($data["nbt_hex"])) {
				$nbt = hex2bin($data["nbt_hex"]);
			} elseif (isset($data["nbt_b64"])) {
				$nbt = base64_decode($data["nbt_b64"], true);
			}

			return ItemFactory::get(
				(int) $data["id"],
				(int) ($data["meta"] ?? 0),
				1,
				$nbt
			);
		};

		$protocols = [];
		foreach (array_diff(scandir(BEDROCK_DATA_PATH . 'creative/'), ["..", "."]) as $protocol) {
			$protocols[] = (int) $protocol;
		}

		sort($protocols);

		$protocolCategories = [];
		foreach ($protocols as $protocol) {
			$index = 1;
			$creativeItemEntry = [];
			if ($protocol >= ProtocolInfo::PROTOCOL_776) {
				foreach ([
					"construction" => CreativeCategory::CONSTRUCTION,
					"nature" => CreativeCategory::NATURE,
					"equipment" => CreativeCategory::EQUIPMENT,
					"items" => CreativeCategory::ITEMS,
				] as $categoryName => $categoryId) {
					$categoryFilePath = BEDROCK_DATA_PATH . 'creative/' . $protocol . '/' . $categoryName . '.json';
					if (!file_exists($categoryFilePath)) {
						$categoryFilePath = BEDROCK_DATA_PATH . 'creative/' . ($protocolCategories[$categoryName] ?? ProtocolInfo::PROTOCOL_776) . '/' . $categoryName . '.json';
					} else {
						$protocolCategories[$categoryName] = $protocol;
					}

					foreach (json_decode(Filesystem::fileGetContents($categoryFilePath), true) as $groupData) {
						$icon = $groupData["group_icon"] === null ? null : $itemDeserializerFunc($groupData["group_icon"]);
						$group = $icon === null ? null : new CreativeGroup($groupData["group_name"], $icon);

						foreach ($groupData["items"] as $itemData) {
							$item = $itemDeserializerFunc($itemData);
							if ($item->getName() === "Unknown") {
								continue;
							}

							$creativeItemEntry[$index++] = new CreativeInventoryEntry($item, $categoryId, $group ?? null);
						}
					}
				}
			} else {
				foreach (json_decode(Filesystem::fileGetContents(BEDROCK_DATA_PATH . 'creative/' . $protocol . '/creative_items.json'), true) as $itemData) {
					$item = $itemDeserializerFunc($itemData);
					if ($item->getName() === "Unknown") {
						continue;
					}

					$creativeItemEntry[$index++] = new CreativeInventoryEntry($item, CreativeCategory::CONSTRUCTION, null);
				}
			}

			$creativeInventoryEntries[$protocol] = $creativeItemEntry;
		}

		$legacyItemIds = [];
		foreach ($creativeInventoryEntries[ProtocolInfo::PROTOCOL_113] ?? [] as $entry) {
			$legacyItemIds[$entry->getItem()->getId()] = true;
		}

		$modernCreativeDataPath = BEDROCK_DATA_PATH . 'creative_2193.json';
		if (file_exists($modernCreativeDataPath)) {
			$modernCreativeData = json_decode(Filesystem::fileGetContents($modernCreativeDataPath), true);
			if (!is_array($modernCreativeData) || !isset($modernCreativeData['groups'], $modernCreativeData['items']) || !is_array($modernCreativeData['groups']) || !is_array($modernCreativeData['items'])) {
				throw new \RuntimeException('Invalid modern creative inventory data');
			}
			$modernItemMapData = json_decode(Filesystem::fileGetContents(BEDROCK_DATA_PATH . 'items/' . ProtocolInfo::PROTOCOL_2193 . '/r16_to_current_item_map.json'), true);
			if (!is_array($modernItemMapData) || !isset($modernItemMapData['simple'], $modernItemMapData['complex']) || !is_array($modernItemMapData['simple']) || !is_array($modernItemMapData['complex'])) {
				throw new \RuntimeException('Invalid modern creative item map');
			}
			$legacyIds = LegacyItemIdToStringIdMap::getInstance(ProtocolInfo::PROTOCOL_2193);
			$modernItemIds = [];
			foreach ($legacyIds->getStringToLegacyMap() as $stringId => $legacyId) {
				$modernItemIds[$stringId] = [$legacyId, 0];
			}
			foreach ($modernItemMapData['simple'] as $legacyStringId => $modernStringId) {
				$legacyId = $legacyIds->stringToLegacy($legacyStringId);
				if ($legacyId !== null) {
					$modernItemIds[$modernStringId] = [$legacyId, 0];
				}
			}
			foreach ($modernItemMapData['complex'] as $legacyStringId => $metadataMap) {
				$legacyId = $legacyIds->stringToLegacy($legacyStringId);
				if ($legacyId === null || !is_array($metadataMap)) {
					continue;
				}
				foreach ($metadataMap as $metadata => $modernStringId) {
					$modernItemIds[$modernStringId] = [$legacyId, (int) $metadata];
				}
			}
			$itemFromModernId = static function (string $stringId) use ($modernItemIds) : ?Item {
				if (!isset($modernItemIds[$stringId])) {
					return null;
				}
				[$id, $meta] = $modernItemIds[$stringId];
				return ItemFactory::get($id, $meta);
			};

			$categoryIds = [
				'construction' => CreativeCategory::CONSTRUCTION,
				'nature' => CreativeCategory::NATURE,
				'equipment' => CreativeCategory::EQUIPMENT,
				'items' => CreativeCategory::ITEMS,
			];
			$groups = [];
			foreach ($modernCreativeData['groups'] as $groupIndex => $groupData) {
				if (!is_array($groupData) || !is_string($groupData['name'] ?? null) || !is_string($groupData['category'] ?? null) || !is_array($groupData['icon'] ?? null) || !is_string($groupData['icon']['id'] ?? null) || !isset($categoryIds[$groupData['category']])) {
					throw new \RuntimeException('Invalid modern creative inventory group');
				}
				$icon = $itemFromModernId($groupData['icon']['id']) ?? ItemFactory::air();
				$groups[$groupIndex] = [
					$categoryIds[$groupData['category']],
					new CreativeGroup($groupData['name'], $icon),
				];
			}

			$modernExtras = array_fill_keys([
				'minecraft:netherite_ingot',
				'minecraft:netherite_block',
				'minecraft:crying_obsidian',
				'minecraft:respawn_anchor',
				'minecraft:amethyst_block',
				'minecraft:calcite',
				'minecraft:tinted_glass',
				'minecraft:copper_block',
				'minecraft:deepslate',
				'minecraft:cobbled_deepslate',
				'minecraft:polished_deepslate',
				'minecraft:deepslate_bricks',
				'minecraft:sculk',
				'minecraft:sculk_sensor',
				'minecraft:moss_block',
				'minecraft:dripstone_block',
				'minecraft:pointed_dripstone',
				'minecraft:powder_snow',
				'minecraft:rooted_dirt',
				'minecraft:azalea',
				'minecraft:flowering_azalea',
				'minecraft:glow_lichen',
				'minecraft:small_dripleaf',
				'minecraft:big_dripleaf',
				'minecraft:bamboo_planks',
				'minecraft:mangrove_planks',
				'minecraft:cherry_planks',
			], true);
			$modernEntries = [];
			foreach ($modernCreativeData['items'] as $index => $itemData) {
				if (!is_array($itemData) || !is_string($itemData['id'] ?? null) || !is_int($itemData['groupId'] ?? null) || !isset($groups[$itemData['groupId']])) {
					throw new \RuntimeException('Invalid modern creative inventory item');
				}
				[$categoryId, $group] = $groups[$itemData['groupId']];
				$item = $itemFromModernId($itemData['id']);
				if ($item !== null && $item->getName() !== 'Unknown' && (isset($legacyItemIds[$item->getId()]) || isset($modernExtras[$itemData['id']]))) {
					$modernEntries[$index + 1] = new CreativeInventoryEntry($item, $categoryId, $group);
				}
			}
			$creativeInventoryEntries[ProtocolInfo::PROTOCOL_2193] = $modernEntries;
		}

		krsort($creativeInventoryEntries);

		return new self($creativeInventoryEntries);
	}

	/** @var DataPacket[] */
	private array $caches = [];

	/**
	 * @param CreativeInventoryEntry[][] $items
	 */
	public function __construct(
		private array $items = []
	) {
	}

	public function getItems(int $protocolVersion) : array{
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion >= $protocol) {
				return $items;
			}
		}

		return [];
	}

	public function clearItems(?int $protocolVersion = null) : void{
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion === null || $protocolVersion >= $protocol) {
				unset($this->items[$protocol]);
			}
		}

		$this->clearCache($protocolVersion);
		krsort($this->items);
	}

	public function addItem(Item $item, int $categoryId = CreativeCategory::CONSTRUCTION, ?CreativeGroup $group = null, ?int $protocolVersion = null) : void{
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion === null || $protocolVersion >= $protocol) {
				$this->items[$protocol][] = new CreativeInventoryEntry($item, $categoryId, $group);
			}
		}

		$this->clearCache($protocolVersion);
		krsort($this->items);
	}

	public function removeItem(Item $item, ?int $protocolVersion = null) : void{
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion === null || $protocolVersion >= $protocol) {
				foreach ($items as $index => $itemEntry) {
					if ($item->equals($itemEntry->getItem(), !($item instanceof Durable))) {
						unset($this->items[$protocol][$index]);
					}
				}
			}
		}

		$this->clearCache($protocolVersion);
		krsort($this->items);
	}

	public function getItemIndex(Item $item, ?int $protocolVersion = null) : int {
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion === null || $protocolVersion >= $protocol) {
				foreach ($items as $index => $itemEntry) {
					if ($item->equals($itemEntry->getItem(), !($item instanceof Durable))) {
						return $index;
					}
				}
			}
		}

		return -1;
	}

	public function getItemFromIndex(int $index, ?int $protocolVersion = null) : ?Item{
		foreach ($this->items as $protocol => $items) {
			if ($protocolVersion === null || $protocolVersion >= $protocol) {
				if (isset($items[$index])) {
					return $items[$index]->getItem();
				}
			}
		}

		return null;
	}

	public function buildPacket(Player $player) : DataPacket{
		$protocolVersion = $player->getProtocolVersion();
		if (isset($this->caches[$protocolVersion])) {
			return $this->caches[$protocolVersion];
		}

		$itemEntries = $this->getItems($protocolVersion);

		$categories = [];
		$groups = [];

		$typeConverter = TypeConverter::getInstance();

		$nextIndex = 0;
		$groupIndexes = [];
		$itemGroupIndexes = [];

		foreach($itemEntries as $k => $entry){
			$group = $entry->getGroup();
			$categoryId = $entry->getCategoryId();
			if($group === null){
				$groupId = PHP_INT_MIN;
			}else{
				$groupId = spl_object_id($group);
				unset($groupIndexes[$categoryId][PHP_INT_MIN]); //start a new anonymous group for this category
			}

			//group object may be reused by multiple categories
			if(!isset($groupIndexes[$categoryId][$groupId])){
				$groupIndexes[$categoryId][$groupId] = $nextIndex++;
				$categories[] = $categoryId;
				$groups[] = $group;
			}
			$itemGroupIndexes[$k] = $groupIndexes[$categoryId][$groupId];
		}

		//creative inventory may have holes if items were unregistered - ensure network IDs used are always consistent
		$itemsEntries = [];
		foreach($itemEntries as $k => $entry){
			$itemsEntries[] = new CreativeItemEntry(
				$k,
				$typeConverter->coreItemStackToNet($entry->getItem(), $protocolVersion),
				$itemGroupIndexes[$k]
			);
		}

		$groupEntries = [];
		if ($protocolVersion >= ProtocolInfo::PROTOCOL_776) {
			foreach ($categories as $index => $categoryId) {
				$group = $groups[$index];
				if ($group === null) {
					$groupEntries[] = new CreativeGroupEntry($categoryId, "", ItemStack::null());
				} else {
					$groupIcon = $group->getIcon();
					//TODO: HACK! In 1.21.60, Workaround glitchy behaviour when an item is used as an icon for a group it
					//doesn't belong to. Without this hack, both instances of the item will show a +, but neither of them
					//will actually expand the group work correctly.
					$groupIcon->getNamedTag()->setInt("___GroupBugWorkaround___", $index);
					$groupEntries[] = new CreativeGroupEntry(
						$categoryId,
						$group->getName(),
						$typeConverter->coreItemStackToNet($groupIcon, $protocolVersion)
					);
				}
			}
		}

		if ($protocolVersion >= ProtocolInfo::PROTOCOL_407) {
			$packet = CreativeContentPacket::create($groupEntries, $itemsEntries);
		} else {
			$items = [];
			foreach ($itemsEntries as $entry) {
				$items[] = $entry->getItem();
			}

			$packet = ContainerSetContentPacket::create(ContainerIds::CREATIVE, $player->getId(), $items, []);
		}

		return $this->caches[$protocolVersion] = $packet;
	}

	public function clearCache(?int $protocolVersion = null) : void{
		if ($protocolVersion === null) {
			$this->caches = [];
		} else {
			foreach ($this->caches as $protocol => $_) {
				if ($protocol >= $protocolVersion) {
					unset($this->caches[$protocol]);
				}
			}
		}
	}
}
