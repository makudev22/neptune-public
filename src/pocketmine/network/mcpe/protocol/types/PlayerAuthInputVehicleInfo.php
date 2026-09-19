<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class PlayerAuthInputVehicleInfo
{
	public function __construct(
		private ?float $vehicleRotationX = null,
		private ?float $vehicleRotationZ = null,
		private ?int $predictedVehicleActorUniqueId = null
	) {
	}

	public function getVehicleRotationX() : ?float
	{
		return $this->vehicleRotationX;
	}

	public function getVehicleRotationZ() : ?float
	{
		return $this->vehicleRotationZ;
	}

	public function getPredictedVehicleActorUniqueId() : ?int
	{
		return $this->predictedVehicleActorUniqueId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$result = new self();
			if ($in->getBool()) {
				$result->vehicleRotationX = $in->getLFloat();
				$result->vehicleRotationZ = $in->getLFloat();
			}
			if ($in->getBool()) {
				$result->predictedVehicleActorUniqueId = $in->getEntityUniqueId();
			}
			return $result;
		}

		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_662) {
			$vehicleRotationX = $in->getLFloat();
			$vehicleRotationZ = $in->getLFloat();
		}
		$predictedVehicleActorUniqueId = $in->getEntityUniqueId();

		return new self($vehicleRotationX ?? 0, $vehicleRotationZ ?? 0, $predictedVehicleActorUniqueId);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$hasRotation = $this->vehicleRotationX !== null && $this->vehicleRotationZ !== null;
			$out->putBool($hasRotation);
			if ($hasRotation) {
				$out->putLFloat($this->vehicleRotationX);
				$out->putLFloat($this->vehicleRotationZ);
			}
			$out->putBool($this->predictedVehicleActorUniqueId !== null);
			if ($this->predictedVehicleActorUniqueId !== null) {
				$out->putEntityUniqueId($this->predictedVehicleActorUniqueId);
			}
			return;
		}

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_662) {
			$out->putLFloat($this->vehicleRotationX);
			$out->putLFloat($this->vehicleRotationZ);
		}
		$out->putEntityUniqueId($this->predictedVehicleActorUniqueId);
	}

	public function isNull() : bool
	{
		return $this->vehicleRotationX === null && $this->vehicleRotationZ === null && $this->predictedVehicleActorUniqueId === null;
	}
}
