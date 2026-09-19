<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

class IslandLayer extends Layer {

	public function fillArea(LayerData $layerData, int $xo, int $yo, int $w, int $h) : void{
		for ($y = 0; $y < $h; $y++) {
			for ($x = 0; $x < $w; $x++) {
				$this->initRandom($xo + $x, $yo + $y);

				$layerData->result[$x + $y * $w] = ($this->nextRandom(10) === 0) ? 1 : 0;
			}
		}

		if ($xo > -$w && $xo <= 0 && $yo > -$h && $yo <= 0) {
			$layerData->result[-$xo + -$yo * $w] = 1;
		}

		$layerData->swap();
	}
}
