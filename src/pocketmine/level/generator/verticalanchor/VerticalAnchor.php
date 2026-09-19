<?php


declare(strict_types=1);

namespace pocketmine\level\generator\verticalanchor;

use pocketmine\level\ChunkManager;

abstract class VerticalAnchor {

	public static function absolute(int $value) : self{
		return new Absolute($value);
	}

	public static function aboveBottom(int $offset) : self{
		return new AboveBottom($offset);
	}

	public static function belowTop(int $offset) : self{
		return new BelowTop($offset);
	}

	public static function bottom() : self{
		return VerticalAnchor::aboveBottom(0);
	}

	public static function top() : self{
		return VerticalAnchor::belowTop(0);
	}

	abstract public function resolveY(ChunkManager $level) : int;

	public static function fromArray(array $data) : self
	{
		if (isset($data['absolute'])) {
			return self::absolute((int) $data['absolute']);
		}

		if (isset($data['above_bottom'])) {
			return self::aboveBottom((int) $data['above_bottom']);
		}

		if (isset($data['below_top'])) {
			return self::belowTop((int) $data['below_top']);
		}

		throw new \InvalidArgumentException('Invalid VerticalAnchor data');
	}

	abstract public function toString() : string;

	abstract public function toArray() : array;
}
