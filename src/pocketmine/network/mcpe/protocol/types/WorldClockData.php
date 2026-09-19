<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

use function count;

final class WorldClockData{

	/**
	 * @param TimeMarkerData[] $timeMarkers
	 * @phpstan-param list<TimeMarkerData> $timeMarkers
	 */
	public function __construct(
		public int $id,
		public string $name,
		public int $time,
		public bool $paused,
		public array $timeMarkers
	){}

	public static function read(NetworkBinaryStream $in) : self{
		$id = $in->getUnsignedVarLong();
		$name = $in->getString();
		$time = $in->getVarInt();
		$paused = $in->getBool();

		$timeMarkers = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$timeMarkers[] = TimeMarkerData::read($in);
		}

		return new self($id, $name, $time, $paused, $timeMarkers);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putUnsignedVarLong($this->id);
		$out->putString($this->name);
		$out->putVarInt($this->time);
		$out->putBool($this->paused);
		$out->putUnsignedVarInt(count($this->timeMarkers));
		foreach($this->timeMarkers as $timeMarker){
			$timeMarker->write($out);
		}
	}
}
