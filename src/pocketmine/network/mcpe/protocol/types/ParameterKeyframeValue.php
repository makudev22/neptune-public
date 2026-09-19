<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;

final class ParameterKeyframeValue{

	public function __construct(
		private float $time,
		private Vector3 $value,
	){}

	public function getTime() : float{ return $this->time; }

	public function getValue() : Vector3{ return $this->value; }

	public static function read(NetworkBinaryStream $in) : self{
		$time = $in->getLFloat();
		$value = $in->getVector3();

		return new self(
			$time,
			$value
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLFloat($this->time);
		$out->putVector3($this->value);
	}
}
