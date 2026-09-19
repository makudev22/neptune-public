<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\network\mcpe\NetworkBinaryStream;

/**
 * @see PacketShapeData
 */
abstract class PrimitiveShapePayload{

	abstract public function getTypeId() : int;

	abstract public function write(NetworkBinaryStream $out) : void;
}
