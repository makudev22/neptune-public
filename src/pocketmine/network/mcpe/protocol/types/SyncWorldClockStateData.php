<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class SyncWorldClockStateData{

	public function __construct(
		public int $clockId,
		public int $time,
		public bool $paused
	){}

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getUnsignedVarLong(),
			$in->getVarInt(),
			$in->getBool()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putUnsignedVarLong($this->clockId);
		$out->putVarInt($this->time);
		$out->putBool($this->paused);
	}
}
