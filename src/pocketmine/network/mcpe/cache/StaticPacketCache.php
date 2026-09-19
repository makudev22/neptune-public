<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\cache;

use pocketmine\network\mcpe\protocol\AvailableActorIdentifiersPacket;
use pocketmine\network\mcpe\protocol\BiomeDefinitionListPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\TrimDataPacket;
use pocketmine\network\mcpe\protocol\types\biome\BiomeDefinitionEntry;
use pocketmine\network\mcpe\protocol\types\TrimMaterial;
use pocketmine\network\mcpe\protocol\types\TrimPattern;
use pocketmine\utils\Color;
use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;

use function array_diff;
use function count;
use function json_decode;
use function krsort;
use function scandir;

use const pocketmine\BEDROCK_DATA_PATH;

class StaticPacketCache
{
	use SingletonTrait;

	private static function make() : self
	{
		$biomeDefs = [];
		foreach (array_diff(scandir($biomeDefsDirectory = BEDROCK_DATA_PATH . 'biomes/'), ["..", "."]) as $protocol) {
			if ($protocol >= ProtocolInfo::PROTOCOL_800) {
				$biomeEntries = json_decode(Filesystem::fileGetContents($biomeDefsDirectory . $protocol . '/biome_definitions.json'), true);
				$entries = [];
				foreach ($biomeEntries as $name => $entry) {
					$entries[] = new BiomeDefinitionEntry(
						$name,
						$entry["id"],
						$entry["temperature"],
						$entry["downfall"],
						$entry["foliageSnow"] ?? 0,
						$entry["redSporeDensity"] ?? 0,
						$entry["blueSporeDensity"] ?? 0,
						$entry["ashDensity"] ?? 0,
						$entry["whiteAshDensity"] ?? 0,
						$entry["depth"],
						$entry["scale"],
						new Color(
							$entry["mapWaterColour"]["r"],
							$entry["mapWaterColour"]["g"],
							$entry["mapWaterColour"]["b"],
							$entry["mapWaterColour"]["a"]
						),
						$entry["rain"],
						count($entry["tags"]) > 0 ? $entry["tags"] : null,
					);
				}

				$biomeDefs[$protocol] = BiomeDefinitionListPacket::create("", $entries);
			} else {
				$biomeDefs[$protocol] = BiomeDefinitionListPacket::create(Filesystem::fileGetContents($biomeDefsDirectory . $protocol . '/biome_definitions.nbt'), []);
			}
		}
		krsort($biomeDefs);

		$actorIds = [];
		foreach (array_diff(scandir($actorIdsDirectory = BEDROCK_DATA_PATH . 'entity/'), ["..", "."]) as $protocol) {
			$actorIds[$protocol] = AvailableActorIdentifiersPacket::create(Filesystem::fileGetContents($actorIdsDirectory . $protocol . '/entity_identifiers.nbt'));
		}
		krsort($actorIds);

		$trimDataPacket = TrimDataPacket::create([
			new TrimPattern("minecraft:ward_armor_trim_smithing_template", "ward"),
			new TrimPattern("minecraft:sentry_armor_trim_smithing_template", "sentry"),
			new TrimPattern("minecraft:snout_armor_trim_smithing_template", "snout"),
			new TrimPattern("minecraft:dune_armor_trim_smithing_template", "dune"),
			new TrimPattern("minecraft:spire_armor_trim_smithing_template", "spire"),
			new TrimPattern("minecraft:tide_armor_trim_smithing_template", "tide"),
			new TrimPattern("minecraft:wild_armor_trim_smithing_template", "wild"),
			new TrimPattern("minecraft:rib_armor_trim_smithing_template", "rib"),
			new TrimPattern("minecraft:coast_armor_trim_smithing_template", "coast"),
			new TrimPattern("minecraft:shaper_armor_trim_smithing_template", "shaper"),
			new TrimPattern("minecraft:eye_armor_trim_smithing_template", "eye"),
			new TrimPattern("minecraft:vex_armor_trim_smithing_template", "vex"),
			new TrimPattern("minecraft:silence_armor_trim_smithing_template", "silence"),
			new TrimPattern("minecraft:wayfinder_armor_trim_smithing_template", "wayfinder"),
			new TrimPattern("minecraft:raiser_armor_trim_smithing_template", "raiser"),
			new TrimPattern("minecraft:host_armor_trim_smithing_template", "host"),
			new TrimPattern("minecraft:bolt_armor_trim_smithing_template", "bolt"),
			new TrimPattern("minecraft:flow_armor_trim_smithing_template", "flow"),
		], [
			new TrimMaterial("quartz", "§h", "minecraft:quartz"),
			new TrimMaterial("iron", "§i", "minecraft:iron_ingot"),
			new TrimMaterial("netherite", "§j", "minecraft:netherite_ingot"),
			new TrimMaterial("redstone", "§m", "minecraft:redstone"),
			new TrimMaterial("copper", "§n", "minecraft:copper_ingot"),
			new TrimMaterial("gold", "§p", "minecraft:gold_ingot"),
			new TrimMaterial("emerald", "§q", "minecraft:emerald"),
			new TrimMaterial("diamond", "§s", "minecraft:diamond"),
			new TrimMaterial("lapis", "§t", "minecraft:lapis_lazuli"),
			new TrimMaterial("amethyst", "§u", "minecraft:amethyst_shard"),
		]);

		return new self(
			$biomeDefs,
			$actorIds,
			$trimDataPacket
		);
	}

	/**
	 * @param BiomeDefinitionListPacket[]       $biomeDefs
	 * @param AvailableActorIdentifiersPacket[] $availableActorIdentifiers
	 */
	public function __construct(
		private array $biomeDefs,
		private array $availableActorIdentifiers,
		private TrimDataPacket $trimDataPacket
	) {
	}

	public function getBiomeDefs(int $protocolVersion) : BiomeDefinitionListPacket
	{
		foreach ($this->biomeDefs as $protocol => $cache) {
			if ($protocolVersion >= $protocol) {
				return $cache;
			}
		}

		return BiomeDefinitionListPacket::create("", []);
	}

	public function getAvailableActorIdentifiers(int $protocolVersion) : AvailableActorIdentifiersPacket
	{
		foreach ($this->availableActorIdentifiers as $protocol => $cache) {
			if ($protocolVersion >= $protocol) {
				return $cache;
			}
		}

		return AvailableActorIdentifiersPacket::create("");
	}

	public function getTrimDataPacket() : TrimDataPacket
	{
		return $this->trimDataPacket;
	}
}
