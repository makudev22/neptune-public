<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraSetInstructionEase
{
	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private int $type,
		private float $duration
	) {
	}

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getType() : int
	{
		return $this->type;
	}

	public function getDuration() : float
	{
		return $this->duration;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$type = $in->getByte();
		$duration = $in->getLFloat();
		return new self($type, $duration);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->type);
		$out->putLFloat($this->duration);
	}
}
