<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class SubChunkRequestResult
{
	public const SUCCESS = 1;
	//why even respond at all in these cases? ...
	public const NO_SUCH_CHUNK = 2;
	public const WRONG_DIMENSION = 3;
	public const NULL_PLAYER = 4;
	public const Y_INDEX_OUT_OF_BOUNDS = 5;
	public const SUCCESS_ALL_AIR = 6;
}
