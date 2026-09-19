<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\convert\constants\commandArgumentTypeIds;

final class CommandArgumentTypeIds407
{
	private function __construct()
	{
		//NOOP
	}

	public const ARG_TYPE_INT = 0x01;
	public const ARG_TYPE_FLOAT = 0x02;
	public const ARG_TYPE_VALUE = 0x03;
	public const ARG_TYPE_WILDCARD_INT = 0x04;
	public const ARG_TYPE_OPERATOR = 0x05;
	public const ARG_TYPE_TARGET = 0x06;

	public const ARG_TYPE_WILDCARD_TARGET = 0x07;

	public const ARG_TYPE_FILEPATH = 0x0e;

	public const ARG_TYPE_STRING = 0x1d;

	public const ARG_TYPE_POSITION = 0x25;

	public const ARG_TYPE_MESSAGE = 0x29;

	public const ARG_TYPE_RAWTEXT = 0x2b;

	public const ARG_TYPE_JSON = 0x2f;

	public const ARG_TYPE_COMMAND = 0x36;

}
