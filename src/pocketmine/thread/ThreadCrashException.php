<?php


declare(strict_types=1);

namespace pocketmine\thread;

final class ThreadCrashException extends ThreadException
{
	private ThreadCrashInfo $crashInfo;

	public function __construct(string $message, ThreadCrashInfo $crashInfo)
	{
		parent::__construct($message);
		$this->crashInfo = $crashInfo;
	}

	public function getCrashInfo() : ThreadCrashInfo
	{
		return $this->crashInfo;
	}
}
