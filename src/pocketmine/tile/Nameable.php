<?php


declare(strict_types=1);

namespace pocketmine\tile;

interface Nameable
{
	public const TAG_CUSTOM_NAME = "CustomName";

	public function getDefaultName() : string;

	public function getName() : string;

	/**
	 * @return void
	 */
	public function setName(string $str);

	public function hasName() : bool;
}
