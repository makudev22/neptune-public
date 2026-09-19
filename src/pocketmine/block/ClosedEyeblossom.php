<?php


declare(strict_types=1);

namespace pocketmine\block;

class ClosedEyeblossom extends Eyeblossom
{
	protected $id = self::CLOSED_EYEBLOSSOM;

	public function getName() : string
	{
		return "Closed Eyeblossom";
	}
}
