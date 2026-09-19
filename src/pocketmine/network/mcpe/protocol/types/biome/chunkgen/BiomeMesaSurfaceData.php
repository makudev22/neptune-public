<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BiomeMesaSurfaceData
{
	public function __construct(
		private int $clayMaterial,
		private int $hardClayMaterial,
		private bool $brycePillars,
		private bool $forest,
	) {
	}

	public function getClayMaterial() : int
	{
		return $this->clayMaterial;
	}

	public function getHardClayMaterial() : int
	{
		return $this->hardClayMaterial;
	}

	public function hasBrycePillars() : bool
	{
		return $this->brycePillars;
	}

	public function hasForest() : bool
	{
		return $this->forest;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$clayMaterial = $in->getLInt();
		$hardClayMaterial = $in->getLInt();
		$brycePillars = $in->getBool();
		$forest = $in->getBool();

		return new self(
			$clayMaterial,
			$hardClayMaterial,
			$brycePillars,
			$forest
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLInt($this->clayMaterial);
		$out->putLInt($this->hardClayMaterial);
		$out->putBool($this->brycePillars);
		$out->putBool($this->forest);
	}
}
