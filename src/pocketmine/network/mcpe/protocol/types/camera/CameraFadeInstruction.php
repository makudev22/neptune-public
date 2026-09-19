<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionColor as Color;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionTime as Time;

final class CameraFadeInstruction
{
	public function __construct(
		private ?Time $time,
		private ?Color $color,
	) {
	}

	public function getTime() : ?Time
	{
		return $this->time;
	}

	public function getColor() : ?Color
	{
		return $this->color;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$time = $in->getOptional(fn () => Time::read($in));
		$color = $in->getOptional(fn () => Color::read($in));
		return new self(
			$time,
			$color
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putOptional($this->time, fn (Time $v) => $v->write($out));
		$out->putOptional($this->color, fn (Color $v) => $v->write($out));
	}
}
