<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use function array_flip;

enum OutputType : int {
	use PacketIntEnumTrait;

	case LAST = 0;
	case SILENT = 1;
	case ALL = 2;
	case DATA_SET = 3;

	private const OUTPUT_TYPES = [ // enum case references requires PHP 8.2
		0 => "lastoutput",
		1 => "silent",
		2 => "alloutput",
		3 => "dataset"
	];

	public function getName() : string{
		return self::OUTPUT_TYPES[$this->value];
	}

	public static function fromName(string $name) : self{
		static $cache = null;
		if($cache === null){
			$cache = array_flip(self::OUTPUT_TYPES);
		}

		$value = $cache[$name] ?? throw new PacketDecodeException("Invalid raw value $name for " . static::class);

		return self::fromPacket($value);
	}
}
