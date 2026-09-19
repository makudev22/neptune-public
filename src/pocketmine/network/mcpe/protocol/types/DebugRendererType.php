<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use function array_flip;

enum DebugRendererType : int
{
	use PacketIntEnumTrait;

	case CLEAR_NUMBER = 1;
	case ADD_CUBE_NUMBER = 2;

	private const DEBUG_RENDERER_TYPES = [ // enum case references requires PHP 8.2
		1 => "cleardebugmarkers",
		2 => "adddebugmarkercube"
	];

	public function getName() : string{
		return self::DEBUG_RENDERER_TYPES[$this->value];
	}

	public static function fromName(string $name) : self{
		static $cache = null;
		if($cache === null){
			$cache = array_flip(self::DEBUG_RENDERER_TYPES);
		}

		$value = $cache[$name] ?? throw new PacketDecodeException("Invalid raw value $name for " . static::class);

		return self::fromPacket($value);
	}
}
