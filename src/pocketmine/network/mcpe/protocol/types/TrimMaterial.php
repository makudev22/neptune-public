<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class TrimMaterial
{
	public function __construct(
		private string $materialId,
		private string $color,
		private string $itemId
	) {
	}

	public function getMaterialId() : string
	{
		return $this->materialId;
	}

	public function getColor() : string
	{
		return $this->color;
	}

	public function getItemId() : string
	{
		return $this->itemId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$materialId = $in->getString();
		$color = $in->getString();
		$itemId = $in->getString();
		return new self($materialId, $color, $itemId);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->materialId);
		$out->putString($this->color);
		$out->putString($this->itemId);
	}
}
