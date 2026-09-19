<?php


declare(strict_types=1);

namespace pocketmine\entity\pathfinder;

use pocketmine\math\Vector2;

class PathPoint extends Vector2
{
	/** @var int */
	public $fScore = 0;
	public $gScore = 0;
	public float $height = 0;

	public function getHashCode() : int
	{
		return ($this->x * 397) ^ $this->y;
	}

	public function equals(Vector2 $v) : bool
	{
		return $this->x == $v->x && $this->y == $v->y;
	}
}
