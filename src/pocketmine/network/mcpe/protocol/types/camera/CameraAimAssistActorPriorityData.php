<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraAimAssistActorPriorityData{

	public function __construct(
		private int $presetIndex,
		private int $categoryIndex,
		private int $actorIndex,
		private int $priority,
	){}

	public function getPresetIndex() : int { return $this->presetIndex; }

	public function getCategoryIndex() : int { return $this->categoryIndex; }

	public function getActorIndex() : int { return $this->actorIndex; }

	public function getPriority() : int { return $this->priority; }

	public static function read(NetworkBinaryStream $in) : self{
		$presetIndex = $in->getLInt();
		$categoryIndex = $in->getLInt();
		$actorIndex = $in->getLInt();
		$priority = $in->getLInt();
		return new self($presetIndex, $categoryIndex, $actorIndex, $priority);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLInt($this->presetIndex);
		$out->putLInt($this->categoryIndex);
		$out->putLInt($this->actorIndex);
		$out->putLInt($this->priority);
	}
}
