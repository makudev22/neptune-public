<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\math\Vector2;
use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraPresetAimAssist
{
	public function __construct(
		private ?string $presetId,
		private ?CameraAimAssistTargetMode $targetMode,
		private ?Vector2 $viewAngle,
		private ?float $distance,
	) {
	}

	public function getPresetId() : ?string
	{
		return $this->presetId;
	}

	public function getTargetMode() : ?CameraAimAssistTargetMode
	{
		return $this->targetMode;
	}

	public function getViewAngle() : ?Vector2
	{
		return $this->viewAngle;
	}

	public function getDistance() : ?float
	{
		return $this->distance;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$presetId = $in->getOptional($in->getString(...));
		$targetMode = $in->getOptional(fn () => CameraAimAssistTargetMode::fromPacket($in->getByte()));
		$viewAngle = $in->getOptional($in->getVector2(...));
		$distance = $in->getOptional($in->getLFloat(...));

		return new self(
			$presetId,
			$targetMode,
			$viewAngle,
			$distance
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putOptional($this->presetId, $out->putString(...));
		$out->putOptional($this->targetMode, fn (CameraAimAssistTargetMode $v) => $out->putByte($v->value));
		$out->putOptional($this->viewAngle, $out->putVector2(...));
		$out->putOptional($this->distance, $out->putLFloat(...));
	}
}
