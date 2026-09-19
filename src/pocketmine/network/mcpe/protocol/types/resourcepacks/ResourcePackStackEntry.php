<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\resourcepacks;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class ResourcePackStackEntry
{
	public function __construct(
		private string $packId,
		private string $version,
		private string $subPackName
	) {
	}

	public function getPackId() : string
	{
		return $this->packId;
	}

	public function getVersion() : string
	{
		return $this->version;
	}

	public function getSubPackName() : string
	{
		return $this->subPackName;
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->packId);
		$out->putString($this->version);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$out->putString($this->subPackName);
		}
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$packId = $in->getString();
		$version = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$subPackName = $in->getString();
		}
		return new self($packId, $version, $subPackName ?? "");
	}
}
