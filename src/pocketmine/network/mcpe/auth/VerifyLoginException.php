<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\auth;

use RuntimeException;

class VerifyLoginException extends RuntimeException
{
	private string $disconnectMessage;

	public function __construct(string $message, string|null $disconnectMessage = null, int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->disconnectMessage = $disconnectMessage ?? $message;
	}

	public function getDisconnectMessage() : string
	{
		return $this->disconnectMessage;
	}

}
