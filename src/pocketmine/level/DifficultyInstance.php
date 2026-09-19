<?php


declare(strict_types=1);

namespace pocketmine\level;

use function max;
use function min;

class DifficultyInstance
{
	/** @var int */
	protected $difficulty;
	/** @var float */
	protected $additionalDifficulty;

	public function __construct(int $difficulty, int $time, int $chunkInhabitedTime, int $moonPhaseFactor)
	{
		$this->difficulty = $difficulty;
		$this->additionalDifficulty = $this->calculateAdditionalDifficulty($difficulty, $time, $chunkInhabitedTime, $moonPhaseFactor);
	}

	private function calculateAdditionalDifficulty(int $difficulty, int $time, int $chunkInhabitedTime, int $moonPhaseFactor) : float
	{
		if ($difficulty === Level::DIFFICULTY_PEACEFUL) {
			return 0;
		} else {
			$f = 0.75 + max(0, min(1, ($time - 72000) / 1440000)) * 0.25;
			$f2 = max(0, min(1, $chunkInhabitedTime / 3600000)) * ($difficulty === Level::DIFFICULTY_HARD ? 1 : 0.75);
			$f2 += max(0, min($f - 0.75, $moonPhaseFactor * 0.25));

			if ($difficulty === Level::DIFFICULTY_EASY) {
				$f2 *= 0.5;
			}

			return $difficulty * ($f + $f2);
		}
	}

	public function getAdditionalDifficulty() : float
	{
		return $this->additionalDifficulty;
	}

	public function getClampedAdditionalDifficulty() : float
	{
		return $this->additionalDifficulty < 2 ? 0 : ($this->additionalDifficulty > 4 ? 1 : ($this->additionalDifficulty - 2) / 2);
	}

	public function getDifficulty() : int
	{
		return $this->difficulty;
	}
}
