<?php


declare(strict_types=1);

namespace raklib\server\ipc;

interface InterThreadChannelWriter
{
	public function write(string $str) : void;
}
