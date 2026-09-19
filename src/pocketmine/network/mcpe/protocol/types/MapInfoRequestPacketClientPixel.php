<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\utils\Color;

use function intdiv;

final class MapInfoRequestPacketClientPixel
{
	private const Y_INDEX_MULTIPLIER = 128;

	public function __construct(
		public Color $color,
		public int $x,
		public int $y
	) {
	}

	public function getColor() : Color
	{
		return $this->color;
	}

	public function getX() : int
	{
		return $this->x;
	}

	public function getY() : int
	{
		return $this->y;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$color = $in->getLInt();
		$index = $in->getLShort();

		$x = $index % self::Y_INDEX_MULTIPLIER;
		$y = intdiv($index, self::Y_INDEX_MULTIPLIER);

		return new self(Color::fromRGBA($color), $x, $y);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putLInt($this->color->toRGBA());
		$out->putLShort($this->x + ($this->y * self::Y_INDEX_MULTIPLIER));
	}
}
