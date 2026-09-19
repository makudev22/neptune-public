<?php


declare(strict_types=1);

namespace pocketmine\level\format\io\region;

use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\level\format\SubChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\BinaryStream;
use pocketmine\world\format\PalettedBlockArray;

/**
 * This format is exactly the same as the PC Anvil format, with the only difference being that the stored data order
 * is XZY instead of YZX for more performance loading and saving worlds.
 */
class PMAnvil extends RegionLevelProvider
{
	use LegacyAnvilChunkTrait;

	protected function deserializeSubChunk(CompoundTag $subChunk) : SubChunk
	{
		if ($subChunk->hasTag("Data")) {
			return new SubChunk(BlockIds::AIR << Block::INTERNAL_METADATA_BITS, [$this->palettizeLegacySubChunkXZY(
				self::readFixedSizeByteArray($subChunk, "Blocks", 4096),
				self::readFixedSizeByteArray($subChunk, "Data", 2048)
			)]);
		} else {
			$stream = new BinaryStream($subChunk->getByteArray("Blocks"));
			[$emptyBlockId, $blockLayers] = $this->deserializeBlockLayers($stream);
			return new SubChunk($emptyBlockId, [$this->translatePalette($blockLayers[0] ?? new PalettedBlockArray($emptyBlockId))]);
		}
	}

	protected static function getRegionFileExtension() : string
	{
		return "mcapm";
	}

	protected static function getPcWorldFormatVersion() : int
	{
		return -1; //Not a PC format, only PocketMine-MP
	}

	public function getWorldHeight() : int
	{
		return 256;
	}
}
