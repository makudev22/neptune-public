<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pocketmine\network\mcpe\NetworkBinaryStream;

/**
 * @see ServerboundDataStorePacket&ClientboundDataStorePacket
 */
abstract class DataStoreOperation{

	abstract public function getTypeId() : DataStoreOperationType;

	abstract public function write(NetworkBinaryStream $out) : void;
}
