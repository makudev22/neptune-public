<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class ServerTelemetryData{

	public function __construct(
		private string $serverId,
		private string $scenarioId,
		private string $worldId,
		private string $ownerId,
	){}

	public function getServerId() : string{ return $this->serverId; }

	public function getScenarioId() : string{ return $this->scenarioId; }

	public function getWorldId() : string{ return $this->worldId; }

	public function getOwnerId() : string{ return $this->ownerId; }

	public static function read(NetworkBinaryStream $in) : self{
		$serverId = $in->getString();
		$scenarioId = $in->getString();
		$worldId = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_818) {
			$ownerId = $in->getString();
		}

		return new self(
			$serverId,
			$scenarioId,
			$worldId,
			$ownerId ?? ""
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->serverId);
		$out->putString($this->scenarioId);
		$out->putString($this->worldId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_818) {
			$out->putString($this->ownerId);
		}
	}
}
