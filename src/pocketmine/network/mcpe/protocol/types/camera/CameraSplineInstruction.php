<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use function count;

final class CameraSplineInstruction{

	/**
	 * @see CameraSetInstructionEaseType
	 *
	 * @param Vector3[]                        $curve
	 * @param Vector2[]|CameraProgressOption[] $progressKeyFrames
	 * @param CameraRotationOption[]           $rotationOptions
	 */
	public function __construct(
		private float $totalTime,
		private int $easeType,
		private array $curve,
		private array $progressKeyFrames,
		private array $rotationOptions,
	){}

	public function getTotalTime() : float{ return $this->totalTime; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int{ return $this->easeType; }

	/**
	 * @return Vector3[]
	 */
	public function getCurve() : array{ return $this->curve; }

	/**
	 * @return Vector2[]|CameraProgressOption[]
	 */
	public function getProgressKeyFrames() : array{ return $this->progressKeyFrames; }

	/**
	 * @return CameraRotationOption[]
	 */
	public function getRotationOptions() : array{ return $this->rotationOptions; }

	public static function read(NetworkBinaryStream $in) : self{
		$totalTime = $in->getLFloat();
		$easeType = $in->getByte();

		$curve = [];
		for($i = 0; $i < $in->getUnsignedVarInt(); ++$i){
			$curve[] = $in->getVector3();
		}

		$progressKeyFrames = [];
		for($i = 0; $i < $in->getUnsignedVarInt(); ++$i){
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
				$progressKeyFrames[] = CameraProgressOption::read($in);
			} else {
				$progressKeyFrames[] = $in->getVector2();
			}
		}

		$rotationOptions = [];
		for($i = 0; $i < $in->getUnsignedVarInt(); ++$i){
			$rotationOptions[] = CameraRotationOption::read($in);
		}

		return new self($totalTime, $easeType, $curve, $progressKeyFrames, $rotationOptions);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLFloat($this->totalTime);
		$out->putByte($this->easeType);

		$out->putUnsignedVarInt(count($this->curve));
		foreach($this->curve as $point){
			$out->putVector3($point);
		}

		$out->putUnsignedVarInt(count($this->progressKeyFrames));
		foreach($this->progressKeyFrames as $keyFrame){
			if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
				$keyFrame->write($out);
			} else {
				$out->putVector2($keyFrame);
			}
		}

		$out->putUnsignedVarInt(count($this->rotationOptions));
		foreach($this->rotationOptions as $option){
			$option->write($out);
		}
	}
}
