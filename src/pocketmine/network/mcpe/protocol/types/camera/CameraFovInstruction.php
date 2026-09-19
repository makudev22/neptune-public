<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraFovInstruction
{
	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private float $fieldOfView,
		private float $easeTime,
		private int $easeType,
		private bool $clear,
	) {
	}

	public function getFieldOfView() : float
	{
		return $this->fieldOfView;
	}

	public function getEaseTime() : float
	{
		return $this->easeTime;
	}

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int
	{
		return $this->easeType;
	}

	public function getClear() : bool
	{
		return $this->clear;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$fieldOfView = $in->getLFloat();
		$easeTime = $in->getLFloat();
		$easeType = $in->getByte();
		$clear = $in->getBool();
		return new self(
			$fieldOfView,
			$easeTime,
			$easeType,
			$clear
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLFloat($this->fieldOfView);
		$out->putLFloat($this->easeTime);
		$out->putByte($this->easeType);
		$out->putBool($this->clear);
	}
}
