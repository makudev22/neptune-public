<?php


declare(strict_types=1);

namespace raklib\protocol;

final class SplitPacketInfo
{
	public function __construct(
		private int $id,
		private int $partIndex,
		private int $totalPartCount
	) {
		//TODO: argument validation
	}

	public function getId() : int
	{
		return $this->id;
	}

	public function getPartIndex() : int
	{
		return $this->partIndex;
	}

	public function getTotalPartCount() : int
	{
		return $this->totalPartCount;
	}
}
