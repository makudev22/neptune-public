<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class ServerJoinInformation{

	public function __construct(
		private ?GatheringJoinInfo $gatheringJoinInfo,
	){}

	public function getGatheringJoinInfo() : ?GatheringJoinInfo{ return $this->gatheringJoinInfo; }

	public static function read(NetworkBinaryStream $in) : self{
		$gatheringJoinInfo = $in->getOptional(fn () => GatheringJoinInfo::read($in));

		return new self(
			$gatheringJoinInfo
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putOptional($this->gatheringJoinInfo, fn(GatheringJoinInfo $info) => $info->write($out));
	}
}
