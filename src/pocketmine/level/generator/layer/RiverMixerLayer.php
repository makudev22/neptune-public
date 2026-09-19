<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

use pocketmine\level\biome\BiomeIds;

class RiverMixerLayer extends Layer {

	protected Layer $parent;
	protected Layer $rivers;

	public function __construct(int $seed, Layer $biomes, Layer $rivers){
		parent::__construct($seed);
		$this->parent = $biomes;
		$this->rivers = $rivers;
	}

	public function fillArea(LayerData $layerData, int $xo, int $yo, int $w, int $h) : void{
		$this->parent->fillArea($layerData, $xo, $yo, $w, $h);

		$riverData = new LayerData();
		$this->rivers->fillArea($riverData, $xo, $yo, $w, $h);

		$size = $w * $h;

		for ($i = 0; $i < $size; $i++) {
			$biomeId = $layerData->parentArea[$i];

			if ($biomeId === BiomeIds::OCEAN || $biomeId === BiomeIds::DEEP_OCEAN) {
				$layerData->result[$i] = $biomeId;
			} else {
				$riverId = $riverData->parentArea[$i];

				if ($riverId === BiomeIds::RIVER) {
					if ($biomeId === BiomeIds::ICE_FLATS) {
						$layerData->result[$i] = BiomeIds::FROZEN_RIVER;
					} elseif ($biomeId === BiomeIds::MUSHROOM_ISLAND || $biomeId === BiomeIds::MUSHROOM_ISLAND_SHORE) {
						$layerData->result[$i] = BiomeIds::MUSHROOM_ISLAND_SHORE;
					}else {
						$layerData->result[$i] = $riverId & 0xFF;
					}
				} else {
					$layerData->result[$i] = $biomeId;
				}
			}
		}

		$layerData->swap();
	}
}
