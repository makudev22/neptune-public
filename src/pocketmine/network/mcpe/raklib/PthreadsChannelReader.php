<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\raklib;

use pmmp\thread\ThreadSafeArray;
use raklib\server\ipc\InterThreadChannelReader;

final class PthreadsChannelReader implements InterThreadChannelReader
{
	/**
	 * @phpstan-param ThreadSafeArray<int, string> $buffer
	 */
	public function __construct(private ThreadSafeArray $buffer)
	{
	}

	public function read() : ?string
	{
		return $this->buffer->shift();
	}
}
