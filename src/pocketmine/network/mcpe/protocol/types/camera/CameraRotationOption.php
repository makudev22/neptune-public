<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraRotationOption{

	public function __construct(
		private Vector3 $value,
		private float $time,
	){}

	public function getValue() : Vector3{ return $this->value; }

	public function getTime() : float{ return $this->time; }

	public static function read(NetworkBinaryStream $in) : self{
		$value = $in->getVector3();
		$time = $in->getLFloat();

		return new self(
			$value,
			$time
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putVector3($this->value);
		$out->putLFloat($this->time);
	}
}
