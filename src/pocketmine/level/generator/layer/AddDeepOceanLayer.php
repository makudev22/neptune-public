<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

use pocketmine\level\biome\BiomeIds;

class AddDeepOceanLayer extends Layer {

	protected Layer $parent;

	public function __construct(int $seed, Layer $parent){
		parent::__construct($seed);
		$this->parent = $parent;
	}

	public function fillArea(LayerData $layerData, int $xo, int $yo, int $w, int $h) : void{
		$px = $xo - 1;
		$py = $yo - 1;
		$pw = $w + 2;
		$ph = $h + 2;

		$this->parent->fillArea($layerData, $px, $py, $pw, $ph);

		for ($y = 0; $y < $h; $y++) {
			for ($x = 0; $x < $w; $x++) {
				$north = $layerData->parentArea[($x + 1) + $y * $pw];
				$east = $layerData->parentArea[($x + 2) + ($y + 1) * $pw];
				$west = $layerData->parentArea[$x + ($y + 1) * $pw];
				$south = $layerData->parentArea[($x + 1) + ($y + 2) * $pw];

				$center = $layerData->parentArea[($x + 1) + ($y + 1) * $pw];

				$count = 0;
				if ($north === 0) $count++;
				if ($east === 0) $count++;
				if ($west === 0) $count++;
				if ($south === 0) $count++;

				$resultIndex = $x + $y * $w;

				if ($center === 0 && $count > 3) {
					$layerData->result[$resultIndex] = BiomeIds::DEEP_OCEAN;
				} else {
					$layerData->result[$resultIndex] = $center;
				}
			}
		}

		$layerData->swap();
	}
}
