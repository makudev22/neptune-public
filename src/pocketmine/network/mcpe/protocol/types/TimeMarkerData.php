<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class TimeMarkerData{

	public function __construct(
		public int $id,
		public string $name,
		public int $time,
		public ?int $period
	){}

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getUnsignedVarLong(),
			$in->getString(),
			$in->getVarInt(),
			$in->getOptional($in->getLInt(...))
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putUnsignedVarLong($this->id);
		$out->putString($this->name);
		$out->putVarInt($this->time);
		$out->putOptional($this->period, $out->putLInt(...));
	}
}
