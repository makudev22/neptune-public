<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class SubChunkPacketHeightMapType
{
	public const NO_DATA = 0;
	public const DATA = 1;
	public const ALL_TOO_HIGH = 2;
	public const ALL_TOO_LOW = 3;
	public const ALL_COPIED = 4;
}
