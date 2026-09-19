<?php


declare(strict_types=1);

namespace raklib\utils;

final class InternetAddress
{
	public function __construct(
		private string $ip,
		private int $port,
		private int $version
	) {
		if ($port < 0 || $port > 65535) {
			throw new \InvalidArgumentException("Invalid port range");
		}
	}

	public function getIp() : string
	{
		return $this->ip;
	}

	public function getPort() : int
	{
		return $this->port;
	}

	public function getVersion() : int
	{
		return $this->version;
	}

	public function __toString()
	{
		return $this->ip . " " . $this->port;
	}

	public function toString() : string
	{
		return $this->__toString();
	}

	public function equals(InternetAddress $address) : bool
	{
		return $this->ip === $address->ip && $this->port === $address->port && $this->version === $address->version;
	}
}
