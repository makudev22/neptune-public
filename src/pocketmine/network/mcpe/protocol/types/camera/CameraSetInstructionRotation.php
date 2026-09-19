<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraSetInstructionRotation
{
	public function __construct(
		private float $pitch,
		private float $yaw,
	) {
	}

	public function getPitch() : float
	{
		return $this->pitch;
	}

	public function getYaw() : float
	{
		return $this->yaw;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$pitch = $in->getLFloat();
		$yaw = $in->getLFloat();
		return new self($pitch, $yaw);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLFloat($this->pitch);
		$out->putLFloat($this->yaw);
	}
}
