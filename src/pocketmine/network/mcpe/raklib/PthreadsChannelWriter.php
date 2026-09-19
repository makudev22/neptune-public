<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\raklib;

use pmmp\thread\ThreadSafeArray;
use raklib\server\ipc\InterThreadChannelWriter;

final class PthreadsChannelWriter implements InterThreadChannelWriter
{
	/**
	 * @phpstan-param ThreadSafeArray<int, string> $buffer
	 */
	public function __construct(private ThreadSafeArray $buffer)
	{
	}

	public function write(string $str) : void
	{
		$this->buffer[] = $str;
	}
}
