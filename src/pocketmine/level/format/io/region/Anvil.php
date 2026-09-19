<?php


declare(strict_types=1);

namespace pocketmine\level\format\io\region;

use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\level\format\SubChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\BinaryStream;
use pocketmine\world\format\PalettedBlockArray;

class Anvil extends RegionLevelProvider
{
	use LegacyAnvilChunkTrait;

	protected function deserializeSubChunk(CompoundTag $subChunk) : SubChunk
	{
		if ($subChunk->hasTag("Data")) {
			return new SubChunk(BlockIds::AIR << Block::INTERNAL_METADATA_BITS, [$this->palettizeLegacySubChunkYZX(
				self::readFixedSizeByteArray($subChunk, "Blocks", 4096),
				self::readFixedSizeByteArray($subChunk, "Data", 2048)
			)]);
		} else {
			$stream = new BinaryStream($subChunk->getByteArray("Blocks"));
			[$emptyBlockId, $blockLayers] = $this->deserializeBlockLayers($stream);
			return new SubChunk($emptyBlockId, [$this->translatePalette($blockLayers[0] ?? new PalettedBlockArray($emptyBlockId))]);
		}
		//ignore legacy light information
	}

	protected static function getRegionFileExtension() : string
	{
		return "mca";
	}

	public static function getPcWorldFormatVersion() : int
	{
		return 19133;
	}

	public function getWorldHeight() : int
	{
		//TODO: add world height options
		return 256;
	}
}
