<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class MoveActorDeltaPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::MOVE_ACTOR_DELTA_PACKET;

	public const FLAG_HAS_X = 0x01;
	public const FLAG_HAS_Y = 0x02;
	public const FLAG_HAS_Z = 0x04;
	public const FLAG_HAS_ROT_X = 0x08;
	public const FLAG_HAS_ROT_Y = 0x10;
	public const FLAG_HAS_ROT_Z = 0x20;

	/** @var int */
	public $entityRuntimeId;
	/** @var int */
	public $flags;
	/** @var float|int */
	public $xPos = 0;
	/** @var float|int */
	public $yPos = 0;
	/** @var float|int */
	public $zPos = 0;
	/** @var float */
	public $xRot = 0.0;
	/** @var float */
	public $yRot = 0.0;
	/** @var float */
	public $zRot = 0.0;
	public bool $onGround = false;
	public bool $teleport = false;
	public bool $forceMoveLocalEntity = false;
	public bool $forceCompletion = false;
	public int $ticks = 0;

	private function maybeReadCoord(int $flag)
	{
		if ($this->flags & $flag) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
				return $this->getLFloat();
			} else {
				return $this->getVarInt();
			}
		}
		return 0;
	}

	private function maybeReadRotation(int $flag) : float
	{
		if ($this->flags & $flag) {
			return $this->getByteRotation();
		}
		return 0.0;
	}

	protected function decodePayload() : void
	{
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			$this->xPos = $this->getBool() ? $this->getLFloat() : null;
			$this->yPos = $this->getBool() ? $this->getLFloat() : null;
			$this->zPos = $this->getBool() ? $this->getLFloat() : null;
			$this->xRot = $this->getBool() ? $this->getByteRotation() : null;
			$this->yRot = $this->getBool() ? $this->getByteRotation() : null;
			$this->zRot = $this->getBool() ? $this->getByteRotation() : null;
			$this->onGround = $this->getBool();
			$this->teleport = $this->getBool();
			$this->forceMoveLocalEntity = $this->getBool();
			$this->forceCompletion = $this->getBool();
			$this->ticks = $this->getUnsignedVarLong();
			return;
		}
		$this->flags = $this->getLShort();
		$this->xPos = $this->maybeReadCoord(self::FLAG_HAS_X);
		$this->yPos = $this->maybeReadCoord(self::FLAG_HAS_Y);
		$this->zPos = $this->maybeReadCoord(self::FLAG_HAS_Z);
		$this->xRot = $this->maybeReadRotation(self::FLAG_HAS_ROT_X);
		$this->yRot = $this->maybeReadRotation(self::FLAG_HAS_ROT_Y);
		$this->zRot = $this->maybeReadRotation(self::FLAG_HAS_ROT_Z);
	}

	private function maybeWriteCoord(int $flag, $val) : void
	{
		if ($this->flags & $flag) {
			if ($this->protocol >= ProtocolInfo::PROTOCOL_419) {
				$this->putLFloat($val);
			} else {
				$this->putVarInt($val);
			}
		}
	}

	private function maybeWriteRotation(int $flag, float $val) : void
	{
		if ($this->flags & $flag) {
			$this->putByteRotation($val);
		}
	}

	protected function encodePayload() : void
	{
		$this->putEntityRuntimeId($this->entityRuntimeId);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_2193) {
			foreach ([$this->xPos, $this->yPos, $this->zPos] as $position) {
				$this->putBool($position !== null);
				if($position !== null){
					$this->putLFloat($position);
				}
			}
			foreach ([$this->xRot, $this->yRot, $this->zRot] as $rotation) {
				$this->putBool($rotation !== null);
				if($rotation !== null){
					$this->putByteRotation($rotation);
				}
			}
			$this->putBool($this->onGround);
			$this->putBool($this->teleport);
			$this->putBool($this->forceMoveLocalEntity);
			$this->putBool($this->forceCompletion);
			$this->putUnsignedVarLong($this->ticks);
			return;
		}
		$this->putLShort($this->flags);
		$this->maybeWriteCoord(self::FLAG_HAS_X, $this->xPos);
		$this->maybeWriteCoord(self::FLAG_HAS_Y, $this->yPos);
		$this->maybeWriteCoord(self::FLAG_HAS_Z, $this->zPos);
		$this->maybeWriteRotation(self::FLAG_HAS_ROT_X, $this->xRot);
		$this->maybeWriteRotation(self::FLAG_HAS_ROT_Y, $this->yRot);
		$this->maybeWriteRotation(self::FLAG_HAS_ROT_Z, $this->zRot);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleMoveActorDelta($this);
	}
}
