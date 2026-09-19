<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraFadeInstructionTime
{
	public function __construct(
		private float $fadeInTime,
		private float $stayTime,
		private float $fadeOutTime
	) {
	}

	public function getFadeInTime() : float
	{
		return $this->fadeInTime;
	}

	public function getStayTime() : float
	{
		return $this->stayTime;
	}

	public function getFadeOutTime() : float
	{
		return $this->fadeOutTime;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$fadeInTime = $in->getLFloat();
		$stayTime = $in->getLFloat();
		$fadeOutTime = $in->getLFloat();
		return new self($fadeInTime, $stayTime, $fadeOutTime);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLFloat($this->fadeInTime);
		$out->putLFloat($this->stayTime);
		$out->putLFloat($this->fadeOutTime);
	}
}
