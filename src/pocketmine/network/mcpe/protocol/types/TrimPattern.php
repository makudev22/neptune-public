<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class TrimPattern
{
	public function __construct(
		private string $itemId,
		private string $patternId
	) {
	}

	public function getItemId() : string
	{
		return $this->itemId;
	}

	public function getPatternId() : string
	{
		return $this->patternId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$itemId = $in->getString();
		$patternId = $in->getString();
		return new self($itemId, $patternId);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->itemId);
		$out->putString($this->patternId);
	}
}
