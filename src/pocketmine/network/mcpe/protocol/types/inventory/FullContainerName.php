<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class FullContainerName
{
	public function __construct(
		private int $containerId,
		private ?int $dynamicId = null
	) {
	}

	public function getContainerId() : int
	{
		return $this->containerId;
	}

	public function getDynamicId() : ?int
	{
		return $this->dynamicId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$containerId = $in->getByte();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_729) {
				$dynamicId = $in->getOptional($in->getLInt(...));
			} else {
				$dynamicId = $in->getLInt();
			}
		}
		return new self($containerId, $dynamicId ?? 0);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$containerId = $this->containerId;
		$out->putByte($containerId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_712) {
			if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_729) {
				$out->putOptional($this->dynamicId, $out->putLInt(...));
			} else {
				$out->putLInt($this->dynamicId ?? 0);
			}
		}
	}
}
