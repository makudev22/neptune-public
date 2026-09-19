<?php


declare(strict_types=1);

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

abstract class Sound extends Vector3
{
	protected int $protocol = ProtocolInfo::CURRENT_PROTOCOL;

	/**
	 * @return DataPacket|DataPacket[]
	 */
	abstract public function encode();

	public function setProtocol(int $protocol) : void
	{
		$this->protocol = $protocol;
	}

	/*
	* It is implemented so that packages are not simply assembled (saving memory)
	*/
	public function isUseProtocol() : bool
	{
		return false;
	}
}
