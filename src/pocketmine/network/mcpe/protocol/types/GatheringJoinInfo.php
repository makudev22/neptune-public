<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\UUID;

final class GatheringJoinInfo{

	public function __construct(
		private string $experienceId,
		private string $experienceName,
		private string $experienceWorldId,
		private string $experienceWorldName,
		private string $creatorId,
		private UUID $targetId,
		private string $scenarioId,
		private string $serverId,
		private string $storeId,
		private string $storeName,
		private bool $presenceConfiguration
	){}

	public function getExperienceId() : string{ return $this->experienceId; }

	public function getExperienceName() : string{ return $this->experienceName; }

	public function getExperienceWorldId() : string{ return $this->experienceWorldId; }

	public function getExperienceWorldName() : string{ return $this->experienceWorldName; }

	public function getCreatorId() : string{ return $this->creatorId; }

	public function getTargetId() : UUID{ return $this->targetId; }

	public function getScenarioId() : string{ return $this->scenarioId; }

	public function getServerId() : string{ return $this->serverId; }

	public function getStoreId() : string{ return $this->storeId; }

	public function getStoreName() : string{ return $this->storeName; }

	public function isPresenceConfiguration() : bool{ return $this->presenceConfiguration; }

	public static function read(NetworkBinaryStream $in) : self{
		$experienceId = $in->getString();
		$experienceName = $in->getString();
		$experienceWorldId = $in->getString();
		$experienceWorldName = $in->getString();
		$creatorId = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$targetId = $in->getUUID();
			$scenarioId = $in->getString();
			$serverId = $in->getString();
		}

		$storeId = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$storeName = $in->getString();
			$presenceConfiguration = $in->getBool();
		}

		return new self(
			$experienceId,
			$experienceName,
			$experienceWorldId,
			$experienceWorldName,
			$creatorId,
			$targetId ?? UUID::fromRandom(),
			$scenarioId ?? "",
			$serverId ?? "",
			$storeId,
			$storeName ?? "",
			$presenceConfiguration ?? ""
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->experienceId);
		$out->putString($this->experienceName);
		$out->putString($this->experienceWorldId);
		$out->putString($this->experienceWorldName);
		$out->putString($this->creatorId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$out->putUUID($this->targetId);
			$out->putString($this->scenarioId);
			$out->putString($this->serverId);
		}

		$out->putString($this->storeId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$out->putString($this->storeName);
			$out->putBool($this->presenceConfiguration);
		}
	}
}
