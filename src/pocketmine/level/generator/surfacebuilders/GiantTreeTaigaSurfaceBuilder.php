<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\block\Dirt;
use pocketmine\level\biome\Biome;
use pocketmine\level\format\Chunk;
use pocketmine\utils\Random;

class GiantTreeTaigaSurfaceBuilder extends DefaultSurfaceBuilder {

	protected SurfaceBuilderConfig $coarseDirtDirtGravel;
	protected SurfaceBuilderConfig $podzolDirtGravel;
	protected SurfaceBuilderConfig $grassDirtGravel;

	public function __construct(){
		$podzol = BlockFactory::get(BlockIds::PODZOL);
		$gravel = BlockFactory::get(BlockIds::GRAVEL);
		$grass = BlockFactory::get(BlockIds::GRASS);
		$dirt = BlockFactory::get(BlockIds::DIRT);
		$coarseDirt = BlockFactory::get(BlockIds::DIRT, Dirt::TYPE_COARSE);

		$this->coarseDirtDirtGravel = new SurfaceBuilderConfig($coarseDirt, $dirt, $gravel);
		$this->podzolDirtGravel = new SurfaceBuilderConfig($podzol, $dirt, $gravel);
		$this->grassDirtGravel = new SurfaceBuilderConfig($grass, $dirt, $gravel);
	}

	public function buildSurface(Random $random, Chunk $chunk, Biome $biome, int $x, int $z, int $startHeight, float $noise, Block $defaultBlock, Block $defaultFluid, int $seaLevel, int $seed, SurfaceBuilderConfig $config) : void{
		if ($noise > 1.75) {
			parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->coarseDirtDirtGravel);
		}elseif ($noise > -0.95) {
			parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->podzolDirtGravel);
		} else {
			parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $this->grassDirtGravel);
		}
	}
}
