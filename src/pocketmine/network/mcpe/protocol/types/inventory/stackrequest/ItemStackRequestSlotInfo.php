<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\FullContainerName;

final class ItemStackRequestSlotInfo
{
	public function __construct(
		private FullContainerName $containerName,
		private int $slotId,
		private int $stackId
	) {
	}

	public function getContainerName() : FullContainerName
	{
		return $this->containerName;
	}

	public function getSlotId() : int
	{
		return $this->slotId;
	}

	public function getStackId() : int
	{
		return $this->stackId;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$containerName = FullContainerName::read($in);
		$slotId = $in->getByte();
		$stackId = $in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 ? $in->getLInt() : $in->readItemStackNetIdVariant();
		return new self($containerName, $slotId, $stackId);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$this->containerName->write($out);
		$out->putByte($this->slotId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putLInt($this->stackId);
		} else {
			$out->writeItemStackNetIdVariant($this->stackId);
		}
	}
}
