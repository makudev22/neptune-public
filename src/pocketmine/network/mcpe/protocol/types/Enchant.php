<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class Enchant
{
	public function __construct(
		private int $id,
		private int $level
	) {
	}

	public function getId() : int
	{
		return $this->id;
	}

	public function getLevel() : int
	{
		return $this->level;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$id = $in->getUnsignedVarInt();
		} else {
			$id = $in->getByte();
		}

		$level = $in->getByte();
		return new self($id, $level);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$out->putUnsignedVarInt($this->id);
		} else {
			$out->putByte($this->id);
		}

		$out->putByte($this->level);
	}
}
