<?php


declare(strict_types=1);

namespace raklib\server;

interface ProtocolAcceptor
{
	public function accepts(int $protocolVersion) : bool;

	public function getPrimaryVersions() : array;
}
