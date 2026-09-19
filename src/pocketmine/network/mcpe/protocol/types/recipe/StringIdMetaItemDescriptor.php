<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class StringIdMetaItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::STRING_ID_META;

	public function __construct(
		private string $id,
		private int $meta
	){
		if($meta < 0){
			throw new \InvalidArgumentException("Meta cannot be negative");
		}
	}

	public function getId() : string{ return $this->id; }

	public function getMeta() : int{ return $this->meta; }

	public static function read(NetworkBinaryStream $in) : self{
		$stringId = $in->getString();
		$meta = $in->getLShort();

		return new self($stringId, $meta);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->id);
		$out->putLShort($this->meta);
	}
}
