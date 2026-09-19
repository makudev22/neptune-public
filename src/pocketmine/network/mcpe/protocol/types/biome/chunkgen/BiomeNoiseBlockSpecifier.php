<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class BiomeNoiseBlockSpecifier{

	public function __construct(
		private string $noise,
		private float $threshold,
		private float $min,
		private float $max,
		private int $block,
	){}

	public function getNoise() : string{ return $this->noise; }

	public function getThreshold() : float{ return $this->threshold; }

	public function getMin() : float{ return $this->min; }

	public function getMax() : float{ return $this->max; }

	public function getBlock() : int{ return $this->block; }

	public static function read(NetworkBinaryStream $in) : self{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$noise = $in->getString();
			$threshold = $in->getLFloat();
			$min = $in->getLFloat();
			$max = $in->getLFloat();
		}

		$block = $in->getLInt();

		return new self(
			$noise ?? "",
			$threshold ?? 0.0,
			$min ?? 0.0,
			$max ?? 0.0,
			$block
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$out->putString($this->noise);
			$out->putLFloat($this->threshold);
			$out->putLFloat($this->min);
			$out->putLFloat($this->max);
		}

		$out->putLInt($this->block);
	}
}
