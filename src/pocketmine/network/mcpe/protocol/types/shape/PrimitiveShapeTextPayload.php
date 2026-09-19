<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\shape;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use pocketmine\utils\Color;

final class PrimitiveShapeTextPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_TEXT;

	public function __construct(
		private string $text,
		private bool $useRotation,
		private ?Color $backgroundColor,
		private bool $depthTest,
		private bool $showBackface,
		private bool $showTextBackface,
	){}

	public function getText() : string{ return $this->text; }

	public function useRotation() : bool{ return $this->useRotation; }

	public function getBackgroundColor() : ?Color{ return $this->backgroundColor; }

	public function hasDepthTest() : bool{ return $this->depthTest; }

	public function hasShowBackface() : bool{ return $this->showBackface; }

	public function hasShowTextBackface() : bool{ return $this->showTextBackface; }

	public static function read(NetworkBinaryStream $in) : self{
		$text = $in->getString();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$useRotation = $in->getBool();
			$backgroundColor = $in->getOptional(fn() => Color::fromARGB($in->getLInt()));
			$depthTest = $in->getBool();
			$showBackface = $in->getBool();
			$showTextBackface = $in->getBool();
		}

		return new self(
			$text,
				$useRotation ?? false,
			$backgroundColor ?? null,
			$depthTest ?? false,
			$showBackface ?? false,
			$showTextBackface ?? false,
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->text);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$out->putBool($this->useRotation);
			$out->putOptional($this->backgroundColor, fn(NetworkBinaryStream $out, Color $color) => $out->putLInt($color->toARGB()));
			$out->putBool($this->depthTest);
			$out->putBool($this->showBackface);
			$out->putBool($this->showTextBackface);
		}
	}
}
