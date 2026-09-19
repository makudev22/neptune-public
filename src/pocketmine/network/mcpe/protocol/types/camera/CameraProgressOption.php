<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraProgressOption{

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private float $value,
		private float $time,
		private int $easeType,
	){}

	public function getValue() : float{ return $this->value; }

	public function getTime() : float{ return $this->time; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int{ return $this->easeType; }

	public static function read(NetworkBinaryStream $in) : self{
		$value = $in->getLFloat();
		$time = $in->getLFloat();
		$easeType = $in->getLInt();

		return new self(
			$value,
			$time,
			$easeType
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLFloat($this->value);
		$out->putLFloat($this->time);
		$out->putLInt($this->easeType);
	}
}
