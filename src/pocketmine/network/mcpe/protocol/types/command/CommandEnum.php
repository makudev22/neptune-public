<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

class CommandEnum
{
	/**
	 * @param string[] $enumValues
	 * @param bool     $isSoft     Whether the enum is dynamic, i.e. can be updated during the game session
	 *
	 * @phpstan-param list<string> $enumValues
	 */
	public function __construct(
		private string $enumName,
		private array $enumValues,
		private bool $isSoft = false
	) {
	}

	public function getName() : string
	{
		return $this->enumName;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getValues() : array
	{
		return $this->enumValues;
	}

	/**
	 * @return bool Whether the enum is dynamic, i.e. can be updated during the game session
	 */
	public function isSoft() : bool
	{
		return $this->isSoft;
	}
}
