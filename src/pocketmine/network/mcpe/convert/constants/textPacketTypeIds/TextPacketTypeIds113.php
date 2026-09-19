<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\constants\textPacketTypeIds;

final class TextPacketTypeIds113
{
	private function __construct()
	{
		//NOOP
	}

	public const TYPE_RAW = 0;
	public const TYPE_CHAT = 1;
	public const TYPE_TRANSLATION = 2;
	public const TYPE_POPUP = 3;
	public const TYPE_TIP = 4;
	public const TYPE_SYSTEM = 5;
	public const TYPE_WHISPER = 6;
	public const TYPE_ANNOUNCEMENT = 7;

}
