<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\shape\PacketShapeData;
use function count;

class DebugDrawePrimitiveShapesPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::PRIMITIVE_SHAPES_PACKET;
	private const MAX_SHAPES = 256;

	/**
	 * @var PacketShapeData[]
	 * @phpstan-var list<PacketShapeData>
	 */
	private array $shapes;

	/**
	 * @generate-create-func
	 * @param PacketShapeData[] $shapes
	 * @phpstan-param list<PacketShapeData> $shapes
	 */
	public static function create(array $shapes) : self
	{
		$result = new self();
		$result->shapes = $shapes;
		return $result;
	}

	/**
	 * @return PacketShapeData[]
	 * @phpstan-return list<PacketShapeData>
	 */
	public function getShapes() : array
	{
		return $this->shapes;
	}

	protected function decodePayload() : void
	{
		$this->shapes = [];
		$len = $this->getUnsignedVarInt();
		if ($len > self::MAX_SHAPES) {
			throw new PacketDecodeException("Too many primitive shapes: $len");
		}
		for ($i = 0; $i < $len; ++$i) {
			$this->shapes[] = PacketShapeData::read($this);
		}
	}

	protected function encodePayload() : void
	{
		$this->putUnsignedVarInt(count($this->shapes));
		foreach ($this->shapes as $shape) {
			$shape->write($this);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleDebugDrawer($this);
	}
}
