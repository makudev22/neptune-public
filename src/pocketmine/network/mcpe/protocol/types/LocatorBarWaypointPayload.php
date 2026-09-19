<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\utils\UUID;

final class LocatorBarWaypointPayload{

	public function __construct(
		public UUID               $groupHandle,
		public LocatorBarWaypoint $waypoint,
		public int                $action
	){}

	public static function read(NetworkBinaryStream $in) : self{
		return new self(
			$in->getUUID(),
			LocatorBarWaypoint::read($in),
			$in->getByte()
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putUUID($this->groupHandle);
		$this->waypoint->write($out);
		$out->putByte($this->action);
	}
}
