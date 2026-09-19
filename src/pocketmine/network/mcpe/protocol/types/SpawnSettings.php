<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class SpawnSettings
{
	public const int BIOME_TYPE_DEFAULT = 0;
	public const int BIOME_TYPE_USER_DEFINED = 1;

	private int $biomeType;
	private string $biomeName;
	private int $dimension;

	public function __construct(int $biomeType, string $biomeName, int $dimension)
	{
		$this->biomeType = $biomeType;
		$this->biomeName = $biomeName;
		$this->dimension = $dimension;
	}

	public function getBiomeType() : int
	{
		return $this->biomeType;
	}

	public function getBiomeName() : string
	{
		return $this->biomeName;
	}

	/**
	 * @see DimensionIds
	 */
	public function getDimension() : int
	{
		return $this->dimension;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$biomeType = $in->getLShort();
			$biomeName = $in->getString();
		}
		$dimension = $in->getVarInt();

		return new self($biomeType ?? 0, $biomeName ?? "", $dimension);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$out->putLShort($this->biomeType);
			$out->putString($this->biomeName);
		}
		$out->putVarInt($this->dimension);
	}
}
