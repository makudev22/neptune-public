<?php


declare(strict_types=1);

namespace raklib\server;

interface ServerEventSource
{
	public function process(ServerInterface $server) : bool;
}
