<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraFadeInstructionColor
{
	public function __construct(
		private float $red,
		private float $green,
		private float $blue,
	) {
	}

	public function getRed() : float
	{
		return $this->red;
	}

	public function getGreen() : float
	{
		return $this->green;
	}

	public function getBlue() : float
	{
		return $this->blue;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$red = $in->getLFloat();
		$green = $in->getLFloat();
		$blue = $in->getLFloat();
		return new self($red, $green, $blue);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLFloat($this->red);
		$out->putLFloat($this->green);
		$out->putLFloat($this->blue);
	}
}
