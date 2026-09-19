<?php


declare(strict_types=1);

namespace raklib\generic;

class PacketHandlingException extends \RuntimeException
{
	/** @phpstan-var DisconnectReason::* */
	private int $disconnectReason;
	/**
	 * @phpstan-param DisconnectReason::* $disconnectReason
	 */
	public function __construct(string $message, int $disconnectReason, int $code = 0, ?\Throwable $previous = null)
	{
		$this->disconnectReason = $disconnectReason;
		parent::__construct($message, $code, $previous);
	}
	/**
	 * @phpstan-return DisconnectReason::*
	 */
	public function getDisconnectReason() : int
	{
		return $this->disconnectReason;
	}
}
