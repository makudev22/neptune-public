<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

class MapTrackedObject
{
	public const TYPE_ENTITY = 0;
	public const TYPE_PLAYER = 0;
	public const TYPE_BLOCK = 1;

	/** @var int */
	public $type;

	/** @var int Only set if is TYPE_ENTITY */
	public $entityUniqueId;

	/** @var int */
	public $x;
	/** @var int */
	public $y;
	/** @var int */
	public $z;

	public static function read(NetworkBinaryStream $in) : self
	{
		$result = new self();
		$result->type = $in->getLInt();
		$result->entityUniqueId = $in->getOptional(fn() => $in->getEntityUniqueId());
		if ($in->getBool()) {
			$in->getBlockPosition($result->x, $result->y, $result->z);
		}
		return $result;
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLInt($this->type);
		$out->putOptional($this->entityUniqueId, fn(int $entityUniqueId) => $out->putEntityUniqueId($entityUniqueId));
		$hasPosition = $this->x !== null && $this->y !== null && $this->z !== null;
		$out->putBool($hasPosition);
		if ($hasPosition) {
			$out->putBlockPosition($this->x, $this->y, $this->z);
		}
	}

}
