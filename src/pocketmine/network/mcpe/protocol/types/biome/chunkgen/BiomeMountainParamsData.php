<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BiomeMountainParamsData
{
	public function __construct(
		private int $steepBlock,
		private bool $northSlopes,
		private bool $southSlopes,
		private bool $westSlopes,
		private bool $eastSlopes,
		private bool $topSlideEnabled,
	) {
	}

	public function getSteepBlock() : int
	{
		return $this->steepBlock;
	}

	public function hasNorthSlopes() : bool
	{
		return $this->northSlopes;
	}

	public function hasSouthSlopes() : bool
	{
		return $this->southSlopes;
	}

	public function hasWestSlopes() : bool
	{
		return $this->westSlopes;
	}

	public function hasEastSlopes() : bool
	{
		return $this->eastSlopes;
	}

	public function hasTopSlideEnabled() : bool
	{
		return $this->topSlideEnabled;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$steepBlock = $in->getLInt();
		$northSlopes = $in->getBool();
		$southSlopes = $in->getBool();
		$westSlopes = $in->getBool();
		$eastSlopes = $in->getBool();
		$topSlideEnabled = $in->getBool();

		return new self(
			$steepBlock,
			$northSlopes,
			$southSlopes,
			$westSlopes,
			$eastSlopes,
			$topSlideEnabled
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLInt($this->steepBlock);
		$out->putBool($this->northSlopes);
		$out->putBool($this->southSlopes);
		$out->putBool($this->westSlopes);
		$out->putBool($this->eastSlopes);
		$out->putBool($this->topSlideEnabled);
	}
}
