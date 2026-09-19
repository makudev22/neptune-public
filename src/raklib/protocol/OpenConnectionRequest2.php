<?php


declare(strict_types=1);

namespace raklib\protocol;

use raklib\utils\InternetAddress;

class OpenConnectionRequest2 extends OfflineMessage
{
	public static $ID = MessageIdentifiers::ID_OPEN_CONNECTION_REQUEST_2;

	public int $clientID;
	public InternetAddress $serverAddress;
	public int $mtuSize;

	protected function encodePayload(PacketSerializer $out) : void
	{
		$this->writeMagic($out);
		$out->putAddress($this->serverAddress);
		$out->putShort($this->mtuSize);
		$out->putLong($this->clientID);
	}

	protected function decodePayload(PacketSerializer $in) : void
	{
		$this->readMagic($in);
		$this->serverAddress = $in->getAddress();
		$this->mtuSize = $in->getShort();
		$this->clientID = $in->getLong();
	}
}
