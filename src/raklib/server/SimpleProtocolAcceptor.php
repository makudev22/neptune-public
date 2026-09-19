<?php


declare(strict_types=1);

namespace raklib\server;

use function in_array;

final class SimpleProtocolAcceptor implements ProtocolAcceptor
{
	public function __construct(
		private array $protocolVersions
	) {
	}

	public function accepts(int $protocolVersion) : bool
	{
		return in_array($protocolVersion, $this->protocolVersions, true);
	}

	public function getPrimaryVersions() : array
	{
		return $this->protocolVersions;
	}
}
