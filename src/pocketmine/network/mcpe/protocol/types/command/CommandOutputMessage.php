<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

class CommandOutputMessage
{
	public bool $isInternal;
	public string $messageId;
	/** @var string[] */
	public array $parameters = [];

}
