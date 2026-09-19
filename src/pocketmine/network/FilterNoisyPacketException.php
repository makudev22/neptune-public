<?php


declare(strict_types=1);

namespace pocketmine\network;

/**
 * Thrown by packet handlers to instruct network sessions to drop repeated packets.
 */
final class FilterNoisyPacketException extends \RuntimeException{

}
