<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

final class ChainedSubCommandValue
{
	public function __construct(
		private string $name,
		private int $type
	) {
	}

	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @see AvailableCommandsPacket::ARG_TYPE_*
	 */
	public function getType() : int
	{
		return $this->type;
	}
}
