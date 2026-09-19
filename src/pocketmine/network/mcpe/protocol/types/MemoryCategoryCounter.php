<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class MemoryCategoryCounter{

	public function __construct(
		private int $category,
		private int $bytes
	){}

	public function getCategory() : int{ return $this->category; }

	public function getBytes() : int{ return $this->bytes; }

	public static function read(NetworkBinaryStream $in) : self{
		$category = $in->getByte();
		$bytes = $in->getLLong();

		return new self(
			$category,
			$bytes
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putByte($this->category);
		$out->putLLong($this->bytes);
	}
}
