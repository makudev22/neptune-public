<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class EntityDiagnosticTimingInfo{

	public function __construct(
		private string $displayName,
		private string $entity,
		private int $timeInNS,
		private int $percentOfTotal,
	){}

	public function getDisplayName() : string{ return $this->displayName; }

	public function getEntity() : string{ return $this->entity; }

	public function getTimeInNS() : int{ return $this->timeInNS; }

	public function getPercentOfTotal() : int{ return $this->percentOfTotal; }

	public static function read(NetworkBinaryStream $in) : self{
		$displayName = $in->getString();
		$entity = $in->getString();
		$timeInNS = $in->getLLong();
		$percentOfTotal = $in->getByte();

		return new self(
			$displayName,
			$entity,
			$timeInNS,
			$percentOfTotal
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->displayName);
		$out->putString($this->entity);
		$out->putLLong($this->timeInNS);
		$out->putByte($this->percentOfTotal);
	}
}
