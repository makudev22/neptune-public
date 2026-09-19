<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class SystemDiagnosticTimingInfo
{

	public function __construct(
		private string $displayName,
		private int    $systemIndex,
		private int    $timeInNS,
		private int    $percentOfTotal,
	)
	{
	}

	public function getDisplayName() : string
	{
		return $this->displayName;
	}

	public function getSystemIndex() : int
	{
		return $this->systemIndex;
	}

	public function getTimeInNS() : int
	{
		return $this->timeInNS;
	}

	public function getPercentOfTotal() : int
	{
		return $this->percentOfTotal;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$displayName = $in->getString();
		$systemIndex = $in->getLLong();
		$timeInNS = $in->getLLong();
		$percentOfTotal = $in->getByte();

		return new self(
			$displayName,
			$systemIndex,
			$timeInNS,
			$percentOfTotal
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->displayName);
		$out->putLLong($this->systemIndex);
		$out->putLLong($this->timeInNS);
		$out->putByte($this->timeInNS);
	}
}
