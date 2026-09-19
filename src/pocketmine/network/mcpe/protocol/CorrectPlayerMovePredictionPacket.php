<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;

class CorrectPlayerMovePredictionPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CORRECT_PLAYER_MOVE_PREDICTION_PACKET;

	public const PREDICTION_TYPE_VEHICLE = 0;
	public const PREDICTION_TYPE_PLAYER = 1;

	public Vector3 $position;
	public Vector3 $delta;
	public bool $onGround;
	public int $tick;
	public int $predictionType;
	public ?Vector2 $vehicleRotation = null;
	public ?float $vehicleAngularVelocity = null;

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_686) {
			$this->predictionType = $this->getByte();
		}
		$this->position = $this->getVector3();
		$this->delta = $this->getVector3();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_686) {
			if ($this->predictionType === self::PREDICTION_TYPE_VEHICLE || $this->protocol >= ProtocolInfo::PROTOCOL_827) {
				$this->vehicleRotation = new Vector2($this->getFloat(), $this->getFloat());

				if ($this->protocol >= ProtocolInfo::PROTOCOL_827) {
					$this->vehicleAngularVelocity = $this->getFloat();
				} else {
					$this->vehicleAngularVelocity = $this->getOptional($this->getFloat(...));
				}
			}
		}
		$this->onGround = $this->getBool();
		$this->tick = $this->getUnsignedVarLong();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_630) {
			$this->predictionType = $this->getByte();
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_686) {
			$this->putByte($this->predictionType);
		}
		$this->putVector3($this->position);
		$this->putVector3($this->delta);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_686) {
			if ($this->predictionType === self::PREDICTION_TYPE_VEHICLE || $this->protocol >= ProtocolInfo::PROTOCOL_827) {
				if ($this->vehicleRotation === null) { // this should never be the case
					throw new \LogicException("CorrectPlayerMovePredictionPackets with type VEHICLE require a vehicleRotation to be provided");
				}

				$this->putFloat($this->vehicleRotation->getX());
				$this->putFloat($this->vehicleRotation->getY());

				if ($this->protocol >= ProtocolInfo::PROTOCOL_827) {
					$this->putFloat($this->vehicleAngularVelocity);
				} else {
					$this->putOptional($this->vehicleAngularVelocity, $this->putFloat(...));
				}
			}
		}
		$this->putBool($this->onGround);
		$this->putUnsignedVarLong($this->tick);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_630) {
			$this->putByte($this->predictionType);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleCorrectPlayerMovePrediction($this);
	}
}
