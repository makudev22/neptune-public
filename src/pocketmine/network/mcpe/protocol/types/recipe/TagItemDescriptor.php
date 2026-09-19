<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class TagItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::TAG;

	public function __construct(
		private string $tag
	){}

	public function getTag() : string{ return $this->tag; }

	public static function read(NetworkBinaryStream $in) : self{
		$tag = $in->getString();

		return new self($tag);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->tag);
	}
}
