<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class ItemNameDescriptor implements ItemDescriptor
{
	public const ID = 1;

	public function __construct(
		private string $name,
		private int $auxValue
	) {
	}

	public function getTypeId() : int
	{
		return self::ID;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getAuxValue() : int
	{
		return $this->auxValue;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		return new self($in->getString(), $in->getVarInt());
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->name);
		$out->putVarInt($this->auxValue);
	}
}
