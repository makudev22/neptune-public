<?php


declare(strict_types=1);

namespace pocketmine\scheduler;

final class BulkCurlTaskOperation
{
	/**
	 * @param string[] $extraHeaders
	 * @param mixed[]  $extraOpts
	 * @phpstan-param list<string> $extraHeaders
	 * @phpstan-param array<int, mixed> $extraOpts
	 */
	public function __construct(
		private string $page,
		private float $timeout = 10,
		private array $extraHeaders = [],
		private array $extraOpts = []
	) {
	}

	public function getPage() : string
	{
		return $this->page;
	}

	public function getTimeout() : float
	{
		return $this->timeout;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getExtraHeaders() : array
	{
		return $this->extraHeaders;
	}

	/**
	 * @return mixed[]
	 * @phpstan-return array<int, mixed>
	 */
	public function getExtraOpts() : array
	{
		return $this->extraOpts;
	}
}
