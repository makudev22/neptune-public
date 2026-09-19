<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class BoolPackSetting extends PackSetting{
	public const ID = PackSettingType::BOOL;

	private bool $value;

	public function __construct(string $name, bool $value){
		parent::__construct($name);
		$this->value = $value;
	}

	public function getValue() : bool{
		return $this->value;
	}

	public function getTypeId() : PackSettingType{
		return self::ID;
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putBool($this->value);
	}

	public static function read(NetworkBinaryStream $in, string $name) : self{
		return new self($name, $in->getBool());
	}
}
