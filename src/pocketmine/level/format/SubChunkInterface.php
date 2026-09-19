<?php


declare(strict_types=1);

namespace pocketmine\level\format;

use pocketmine\world\format\PalettedBlockArray;

interface SubChunkInterface
{
	public function isEmpty(bool $checkLight = true) : bool;

	public function getEmptyBlockId() : int;

	public function getBlockId(int $x, int $y, int $z) : int;

	public function setBlockId(int $x, int $y, int $z, int $id) : bool;

	public function getBlockData(int $x, int $y, int $z) : int;

	public function setBlockData(int $x, int $y, int $z, int $data) : bool;

	public function getFullBlock(int $x, int $y, int $z) : int;

	public function setFullBlock(int $x, int $y, int $z, int $block) : bool;

	public function setBlock(int $x, int $y, int $z, ?int $id = null, ?int $data = null) : bool;

	public function getBlockLight(int $x, int $y, int $z) : int;

	public function setBlockLight(int $x, int $y, int $z, int $level) : bool;

	public function getBlockSkyLight(int $x, int $y, int $z) : int;

	public function setBlockSkyLight(int $x, int $y, int $z, int $level) : bool;

	public function getHighestBlockAt(int $x, int $z) : int;

	public function getBlockLightColumn(int $x, int $z) : string;

	public function getBlockSkyLightColumn(int $x, int $z) : string;

	/**
	 * @return PalettedBlockArray[]
	 */
	public function getBlockLayers() : array;

	public function getBlockSkyLightArray() : string;

	public function setBlockSkyLightArray(string $data);

	public function getBlockLightArray() : string;

	public function setBlockLightArray(string $data);
}
