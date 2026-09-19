<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

final class ChainedSubCommandData
{
	/**
	 * @param ChainedSubCommandValue[] $values
	 */
	public function __construct(
		private string $name,
		private array $values
	) {
	}

	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @return ChainedSubCommandValue[]
	 */
	public function getValues() : array
	{
		return $this->values;
	}
}
