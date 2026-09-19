<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

/**
 * @see ClientboundTextureShiftPacket
 */
final class TextureShiftAction{
	public const INVALID = 0;
	public const INITIALIZE = 1;
	public const START = 2;
	public const SET_ENABLED = 3;
	public const SYNC = 4;
}
