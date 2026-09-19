<?php


declare(strict_types=1);

namespace pocketmine\level\generator\surfacebuilders;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\level\biome\Biome;
use pocketmine\level\biome\BiomeNoise;
use pocketmine\level\format\Chunk;
use pocketmine\level\generator\noise\synth\PerlinSimplexNoise;
use pocketmine\utils\Random;

class SwampSurfaceBuilder extends DefaultSurfaceBuilder {

	protected PerlinSimplexNoise $infoNoise;

	public function __construct(){
		$this->infoNoise = BiomeNoise::getInstance()->getInfoNoise();
	}

	public function buildSurface(Random $random, Chunk $chunk, Biome $biome, int $x, int $z, int $startHeight, float $noise, Block $defaultBlock, Block $defaultFluid, int $seaLevel, int $seed, SurfaceBuilderConfig $config) : void{
		$groundValue = $this->infoNoise->getValue2D($x * 0.25, $z * 0.25);
		if ($groundValue > 0.0) {
			$i = $x & Chunk::COORD_MASK;
			$j = $z & Chunk::COORD_MASK;

			for ($k = $startHeight; $k >= 0; --$k) {
				$block = BlockFactory::fromFullBlock($chunk->getFullBlock($i, $k, $j));

				if ($block->getId() === BlockIds::AIR) {
					if ($k == 62 && !$block->isSameType($defaultFluid)) {
						$chunk->setFullBlock($i, $k, $j, $defaultFluid->getFullId());
					}
					break;
				}
			}
		}

		parent::buildSurface($random, $chunk, $biome, $x, $z, $startHeight, $noise, $defaultBlock, $defaultFluid, $seaLevel, $seed, $config);
	}
}
