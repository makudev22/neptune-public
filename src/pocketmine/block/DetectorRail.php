<?php


declare(strict_types=1);

namespace pocketmine\block;

class DetectorRail extends RedstoneRail
{
	protected $id = self::DETECTOR_RAIL;

	public function getName() : string
	{
		return "Detector Rail";
	}

	//TODO
}
