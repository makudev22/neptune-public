<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

final class CommandOverload
{
	/**
	 * @param CommandParameter[] $parameters
	 */
	public function __construct(
		private bool $chaining,
		private array $parameters
	) {
		(function (CommandParameter ...$parameters) : void {})(...$parameters);
	}

	public function isChaining() : bool
	{
		return $this->chaining;
	}

	/**
	 * @return CommandParameter[]
	 */
	public function getParameters() : array
	{
		return $this->parameters;
	}

}
