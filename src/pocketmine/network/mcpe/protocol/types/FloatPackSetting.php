<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class FloatPackSetting extends PackSetting{
	public const ID = PackSettingType::FLOAT;

	private float $value;

	public function __construct(string $name, float $value){
		parent::__construct($name);
		$this->value = $value;
	}

	public function getValue() : float{
		return $this->value;
	}

	public function getTypeId() : PackSettingType{
		return self::ID;
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putLFloat($this->value);
	}

	public static function read(NetworkBinaryStream $in, string $name) : self{
		return new self($name, $in->getLFloat());
	}
}
