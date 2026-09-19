<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class MolangItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::MOLANG;

	public function __construct(
		private string $molangExpression,
		private int $molangVersion
	){}

	public function getMolangExpression() : string{ return $this->molangExpression; }

	public function getMolangVersion() : int{ return $this->molangVersion; }

	public static function read(NetworkBinaryStream $in) : self{
		$expression = $in->getString();
		$version = $in->getLShort();

		return new self($expression, $version);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->molangExpression);
		$out->putLShort($this->molangVersion);
	}
}
