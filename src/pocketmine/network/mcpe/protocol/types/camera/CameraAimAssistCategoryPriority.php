<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraAimAssistCategoryPriority
{
	public function __construct(
		private string $identifier,
		private int $priority
	) {
	}

	public function getIdentifier() : string
	{
		return $this->identifier;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$identifier = $in->getString();
		$priority = $in->getLInt();
		return new self(
			$identifier,
			$priority
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->identifier);
		$out->putLInt($this->priority);
	}
}
