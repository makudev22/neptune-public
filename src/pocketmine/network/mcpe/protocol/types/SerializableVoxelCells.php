<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use function count;

final class SerializableVoxelCells{

	/**
	 * @param list<int> $storage
	 */
	public function __construct(
		private int $xSize,
		private int $ySize,
		private int $zSize,
		private array $storage
	){}

	public function getXSize() : int{ return $this->xSize; }

	public function getYSize() : int{ return $this->ySize; }

	public function getZSize() : int{ return $this->zSize; }

	/**
	 * @return list<int>
	 */
	public function getStorage() : array{ return $this->storage; }

	public static function read(NetworkBinaryStream $in) : self{
		$xSize = $in->getByte();
		$ySize = $in->getByte();
		$zSize = $in->getByte();

		$storage = [];
		for($i = 0; $i < $in->getUnsignedVarInt(); ++$i){
			$storage[] = $in->getByte();
		}
		return new self(
			$xSize,
			$ySize,
			$zSize,
			$storage
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putByte($this->ySize);
		$out->putByte($this->ySize);
		$out->putByte($this->zSize);

		$out->putUnsignedVarInt(count($this->storage));
		foreach($this->storage as $value){
			$out->putByte($value);
		}
	}
}
