<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class WhiskerScopeDataSummary{

	public function __construct(
		private string $label,
		private string $indentation,
		private int $totalHighCostNS,
		private int $totalMidCostNS,
		private int $totalLowCostNS,
	){}

	public function getLabel() : string{ return $this->label; }

	public function getIndentation() : string{ return $this->indentation; }

	public function getTotalHighCostNS() : int{ return $this->totalHighCostNS; }

	public function getTotalMidCostNS() : int{ return $this->totalMidCostNS; }

	public function getTotalLowCostNS() : int{ return $this->totalLowCostNS; }

	public static function read(NetworkBinaryStream $in) : self{
		$label = $in->getString();
		$indentation = $in->getString();
		$totalHighCostNS = $in->getLLong();
		$totalMidCostNS = $in->getLLong();
		$totalLowCostNS = $in->getLLong();

		return new self(
			$label,
			$indentation,
			$totalHighCostNS,
			$totalMidCostNS,
			$totalLowCostNS
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->label);
		$out->putString($this->indentation);
		$out->putLLong($this->totalHighCostNS);
		$out->putLLong($this->totalMidCostNS);
		$out->putLLong($this->totalLowCostNS);
	}
}
