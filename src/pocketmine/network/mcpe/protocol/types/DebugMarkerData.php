<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\Color;

final class DebugMarkerData{

	public function __construct(
		private string $text,
		private Vector3 $position,
		private Color $color,
		private int $durationMillis
	){}

	public function getText() : string{ return $this->text; }

	public function getPosition() : Vector3{ return $this->position; }

	public function getColor() : Color{ return $this->color; }

	public function getDurationMillis() : int{ return $this->durationMillis; }

	public static function read(NetworkBinaryStream $in) : self{
		$text = $in->getString();
		$position = $in->getVector3();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_897) {
			$color = Color::fromARGB($in->getLInt());
		} else {
			$color = new Color((int) $in->getLFloat(), (int) $in->getLFloat(), (int) $in->getLFloat(), (int) $in->getLFloat());
		}

		$durationMillis = $in->getLLong();

		return new self(
			$text,
			$position,
			$color,
			$durationMillis
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->text);
		$out->putVector3($this->position);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_897) {
			$out->putLInt($this->color->toARGB());
		} else {
			$out->putLFloat($this->color->getR());
			$out->putLFloat($this->color->getG());
			$out->putLFloat($this->color->getB());
			$out->putLFloat($this->color->getA());
		}
		$out->putLLong($this->durationMillis);
	}
}
