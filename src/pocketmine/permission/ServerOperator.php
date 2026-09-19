<?php


declare(strict_types=1);

namespace pocketmine\permission;

interface ServerOperator
{
	/**
	 * Checks if the current object has operator permissions
	 */
	public function isOp() : bool;

	/**
	 * Sets the operator permission for the current object
	 */
	public function setOp(bool $value);
}
