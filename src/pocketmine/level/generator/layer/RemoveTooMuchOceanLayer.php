<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

class RemoveTooMuchOceanLayer extends Layer {
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

				$resultIndex = $x + $y * $w;

				$layerData->result[$resultIndex] = $center;

				$this->initRandom($x + $xo, $y + $yo);

				if (
					$center === 0 &&
					$north === 0 &&
					$east === 0 &&
					$west === 0 &&
					$south === 0 &&
					$this->nextRandom(2) === 0
				) {
					$layerData->result[$resultIndex] = Layer::WARM_ID;
				}
			}
		}

		$layerData->swap();
	}
}
