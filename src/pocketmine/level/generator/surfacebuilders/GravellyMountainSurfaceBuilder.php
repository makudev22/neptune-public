<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\level\biome\Biome;
use pocketmine\level\format\Chunk;
use pocketmine\utils\Random;

class GravellyMountainSurfaceBuilder extends DefaultSurfaceBuilder {

	protected SurfaceBuilderConfig $stoneStoneGravel;
	protected SurfaceBuilderConfig $grassDirtGravel;
	protected SurfaceBuilderConfig $gravelGravelGravel;

	public function __construct(){
		$stone = BlockFactory::get(BlockIds::STONE);
		$gravel = BlockFactory::get(BlockIds::GRAVEL);
		$grass = BlockFactory::get(BlockIds::GRASS);
		$dirt = BlockFactory::get(BlockIds::DIRT);

		$this->stoneStoneGravel = new SurfaceBuilderConfig($stone, $stone, $gravel);
		$this->grassDirtGravel = new SurfaceBuilderConfig($grass, $dirt, $gravel);
		$this->gravelGravelGravel = new SurfaceBuilderConfig($gravel, $gravel, $gravel);
	}

	public function buildSurface(Random $random, Chunk $chunk, Biome $biome, int $x, int $z, int $startHeight, float $noise, Block $defaultBlock, Block $defaultFluid, int $seaLevel, int $seed, SurfaceBuilderConfig $config) : void{
		if (!($noise < -1.0) && !($noise > 2.0)) {
			if ($noise > 1.0) {
				parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->stoneStoneGravel);
			} else {
				parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->grassDirtGravel);
			}
		} else {
			parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->gravelGravelGravel);
		}
	}
}
