<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class EducationSettingsAgentCapabilities
{
	private ?bool $canModifyBlocks;

	public function __construct(?bool $canModifyBlocks)
	{
		$this->canModifyBlocks = $canModifyBlocks;
	}

	public function getCanModifyBlocks() : ?bool
	{
		return $this->canModifyBlocks;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$canModifyBlocks = $in->getBool() ? $in->getBool() : null;
		return new self($canModifyBlocks);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		if ($this->canModifyBlocks !== null) {
			$out->putBool(true);
			$out->putBool($this->canModifyBlocks);
		} else {
			$out->putBool(false);
		}
	}
}
