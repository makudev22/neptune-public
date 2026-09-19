<?php


declare(strict_types=1);

namespace pocketmine\block;

class OpenEyeblossom extends Eyeblossom
{
	protected $id = self::OPEN_EYEBLOSSOM;

	public function getName() : string
	{
		return "Open Eyeblossom";
	}
}
