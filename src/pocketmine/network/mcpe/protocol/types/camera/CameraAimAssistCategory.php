<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraAimAssistCategory
{
	public function __construct(
		private string $name,
		private CameraAimAssistCategoryPriorities $priorities
	) {
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getPriorities() : CameraAimAssistCategoryPriorities
	{
		return $this->priorities;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$name = $in->getString();
		$priorities = CameraAimAssistCategoryPriorities::read($in);
		return new self(
			$name,
			$priorities
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->name);
		$this->priorities->write($out);
	}
}
