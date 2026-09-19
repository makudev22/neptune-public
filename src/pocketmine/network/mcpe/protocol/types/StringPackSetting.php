<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class StringPackSetting extends PackSetting{
	public const ID = PackSettingType::STRING;

	private string $value;

	public function __construct(string $name, string $value){
		parent::__construct($name);
		$this->value = $value;
	}

	public function getValue() : string{
		return $this->value;
	}

	public function getTypeId() : PackSettingType{
		return self::ID;
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->value);
	}

	public static function read(NetworkBinaryStream $in, string $name) : self{
		return new self($name, $in->getString());
	}
}
