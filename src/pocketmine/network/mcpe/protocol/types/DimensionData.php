<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class DimensionData
{
	public function __construct(
		private int $maxHeight,
		private int $minHeight,
		private int $generator,
		private int $dimensionType
	) {
	}

	public function getMaxHeight() : int{ return $this->maxHeight; }

	public function getMinHeight() : int{ return $this->minHeight; }

	public function getGenerator() : int{ return $this->generator; }

	public function getDimensionType() : int{ return $this->dimensionType; }

	public static function read(NetworkBinaryStream $in) : self
	{
		$maxHeight = $in->getVarInt();
		$minHeight = $in->getVarInt();
		$generator = $in->getVarInt();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$dimensionType = $in->getVarInt();
		}

		return new self($maxHeight, $minHeight, $generator, $dimensionType ?? 0);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putVarInt($this->maxHeight);
		$out->putVarInt($this->minHeight);
		$out->putVarInt($this->generator);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$out->putVarInt($this->generator);
		}
	}
}
