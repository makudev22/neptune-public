<?php


declare(strict_types=1);

namespace raklib\server\ipc;

interface InterThreadChannelReader
{
	public function read() : ?string;
}
