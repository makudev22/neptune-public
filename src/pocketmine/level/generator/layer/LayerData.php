<?php


declare(strict_types=1);

namespace pocketmine\level\generator\layer;

use function array_fill;

class LayerData {
	/** @var int[] */
	public array $parentArea = [];
	/** @var int[] */
	public array $result = [];

	public function __construct() {
		$size = 32 * 32;

		$this->parentArea = array_fill(0, $size, 0);
		$this->result = array_fill(0, $size, 0);
	}

	public function swap() : void {
		[$this->parentArea, $this->result] = [$this->result, $this->parentArea];
	}
}
