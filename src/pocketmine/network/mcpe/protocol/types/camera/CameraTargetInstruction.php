<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraTargetInstruction
{
	public function __construct(
		private ?Vector3 $targetCenterOffset,
		private int $actorUniqueId
	) {
	}

	public function getTargetCenterOffset() : ?Vector3
	{
		return $this->targetCenterOffset;
	}

	public function getActorUniqueId() : int
	{
		return $this->actorUniqueId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$targetCenterOffset = $in->getOptional(fn () => $in->getVector3());
		$actorUniqueId = $in->getLLong();
		return new self(
			$targetCenterOffset,
			$actorUniqueId
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putOptional($this->targetCenterOffset, fn (Vector3 $v) => $out->putVector3($v));
		$out->putLLong($this->actorUniqueId);
	}
}
