<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraSplineDefinition{

	public function __construct(
		private string $name,
		private CameraSplineInstruction $instruction,
	){}

	public function getName() : string { return $this->name; }

	public function getInstruction() : CameraSplineInstruction { return $this->instruction; }

	public static function read(NetworkBinaryStream $in) : self{
		$name = $in->getString();
		$instruction = CameraSplineInstruction::read($in);
		return new self($name, $instruction);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->name);
		$this->instruction->write($out);
	}
}
