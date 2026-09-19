<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class BiomeSurfaceMaterialAdjustmentData
{
	/**
	 * @param BiomeElementData[] $adjustments
	 */
	public function __construct(
		private array $adjustments,
	) {
	}

	/**
	 * @return BiomeElementData[]
	 */
	public function getAdjustments() : array
	{
		return $this->adjustments;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$adjustments = [];

		for ($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i) {
			$adjustments[] = BiomeElementData::read($in);
		}

		return new self($adjustments);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->adjustments));
		foreach ($this->adjustments as $adjustment) {
			$adjustment->write($out);
		}
	}
}
