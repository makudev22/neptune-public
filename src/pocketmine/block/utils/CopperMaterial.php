<?php


declare(strict_types=1);

namespace pocketmine\block\utils;

/**
 * Represents copper blocks that have oxidized and waxed variations.
 */
interface CopperMaterial{

	public function getOxidation() : int;

	public function getPreviousOxidationId() : ?int;

	public function isWaxed() : bool;

	public function getWaxedId() : int;

	public function getNonWaxedId() : int;

}
