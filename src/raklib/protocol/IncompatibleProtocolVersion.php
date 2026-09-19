<?php


declare(strict_types=1);

namespace raklib\protocol;

class IncompatibleProtocolVersion extends OfflineMessage
{
	public static $ID = MessageIdentifiers::ID_INCOMPATIBLE_PROTOCOL_VERSION;

	public int $protocolVersion;
	public int $serverId;

	public static function create(int $protocolVersion, int $serverId) : self
	{
		$result = new self();
		$result->protocolVersion = $protocolVersion;
		$result->serverId = $serverId;
		return $result;
	}

	protected function encodePayload(PacketSerializer $out) : void
	{
		$out->putByte($this->protocolVersion);
		$this->writeMagic($out);
		$out->putLong($this->serverId);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->protocolVersion = $in->getByte();
		$this->readMagic($in);
		$this->serverId = $in->getLong();
	}
}
