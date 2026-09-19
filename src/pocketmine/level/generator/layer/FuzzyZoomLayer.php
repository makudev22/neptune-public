<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

class FuzzyZoomLayer extends ZoomLayer {

	public static function zoom(int $seed, Layer $sup, int $count) : Layer{
		$result = $sup;
		for ($i = 0; $i < $count; $i++) {
			$result = new FuzzyZoomLayer($seed + $i, $result);
		}

		return $result;
	}

	public function modeOrRandom(int $a, int $b, int $c, int $d) : int{
		return $this->random([$a, $b, $c, $d]);
	}
}
