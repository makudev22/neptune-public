<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

/**
 * @see ServerPresenceInfoPacket&ServerJoinInformation
 */
final class PresenceInfo{
	public function __construct(
		private ?string $experienceName,
		private ?string $worldName,
		private string $richPresenceId
	){}

	public function getExperienceName() : ?string{ return $this->experienceName; }

	public function getWorldName() : ?string{ return $this->worldName; }

	public function getRichPresenceId() : string{ return $this->richPresenceId; }

	public static function read(NetworkBinaryStream $in) : self{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$experienceName = $in->getOptional($in->getString(...));
			$worldName = $in->getOptional($in->getString(...));
			$richPresenceId = $in->getString();
		} else {
			$experienceName = $in->getString();
			$worldName = $in->getString();
		}

		return new self($experienceName, $worldName, $richPresenceId ?? "");
	}

	public function write(NetworkBinaryStream $out) : void{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$out->putOptional($this->experienceName, $out->putString(...));
			$out->putOptional($this->worldName, $out->putString(...));
			$out->putString($this->richPresenceId);
		} else {
			$out->putString($this->experienceName);
			$out->putString($this->worldName);
		}
	}
}
