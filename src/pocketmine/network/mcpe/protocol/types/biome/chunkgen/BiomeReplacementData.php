<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class BiomeReplacementData{

	/**
	 * @param int[] $targetBiomes
	 */
	public function __construct(
		private int $biome,
		private int $dimension,
		private array $targetBiomes,
		private float $amount,
		private int $replacementIndex
	){}

	public function getBiome() : int{ return $this->biome; }

	public function getDimension() : int{ return $this->dimension; }

	/**
	 * @return int[]
	 */
	public function getTargetBiomes() : array{ return $this->targetBiomes; }

	public function getAmount() : float{ return $this->amount; }

	public function getReplacementIndex() : int{ return $this->replacementIndex; }

	public static function read(NetworkBinaryStream $in) : self{
		$biome = $in->getSignedLShort();
		$dimension = $in->getVarInt();
		$targetBiomes = [];
		for($i = 0; $i < $in->getUnsignedVarInt(); ++$i){
			$targetBiomes[] = $in->getSignedLShort();
		}
		$amount = $in->getLFloat();
		$replacementIndex = $in->getLInt();
		return new self($biome, $dimension, $targetBiomes, $amount, $replacementIndex);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putShort($this->biome);
		$out->putVarInt($this->dimension);
		$out->putUnsignedVarInt(count($this->targetBiomes));
		foreach($this->targetBiomes as $biome){
			$out->putShort($biome);
		}
		$out->putLFloat($this->amount);
		$out->putLInt($this->replacementIndex);
	}
}
