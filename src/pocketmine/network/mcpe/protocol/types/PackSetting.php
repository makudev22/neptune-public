<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

abstract class PackSetting{
	public function __construct(
		private readonly string $name,
	){}

	public function getName() : string{ return $this->name; }

	abstract public function getTypeId() : PackSettingType;

	abstract public function write(NetworkBinaryStream $out) : void;
}
