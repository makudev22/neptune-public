<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pocketmine\network\mcpe\NetworkBinaryStream;

abstract class DataStoreUpdateValue{

	abstract public function getTypeId() : int;

	abstract public function write(NetworkBinaryStream $out) : void;
}
