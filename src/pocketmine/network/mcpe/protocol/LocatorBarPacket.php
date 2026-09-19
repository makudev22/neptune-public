<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\LocatorBarWaypointPayload;

use function count;

class LocatorBarPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::LOCATOR_BAR_PACKET;

	/**
	 * @var LocatorBarWaypointPayload[]
	 * @phpstan-var list<LocatorBarWaypointPayload>
	 */
	public array $waypoints = [];

	/**
	 * @generate-create-func
	 * @param LocatorBarWaypointPayload[] $waypoints
	 * @phpstan-param list<LocatorBarWaypointPayload> $waypoints
	 */
	public static function create(array $waypoints) : self{
		$result = new self();
		$result->waypoints = $waypoints;
		return $result;
	}

	protected function decodePayload() : void{
		$this->waypoints = [];
		for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i){
			$this->waypoints[] = LocatorBarWaypointPayload::read($this);
		}
	}

	protected function encodePayload() : void{
		$this->putUnsignedVarInt(count($this->waypoints));
		foreach($this->waypoints as $waypoint){
			$waypoint->write($this);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleLocatorBar($this);
	}
}
