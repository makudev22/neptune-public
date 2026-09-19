<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\EntityDiagnosticTimingInfo;
use pocketmine\network\mcpe\protocol\types\MemoryCategoryCounter;
use pocketmine\network\mcpe\protocol\types\SystemDiagnosticTimingInfo;
use pocketmine\network\mcpe\protocol\types\WhiskerScopeDataSummary;
use function count;

class ServerboundDiagnosticsPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_DIAGNOSTICS_PACKET;

	public float $avgFps;
	public float $avgServerSimTickTimeMS;
	public float $avgClientSimTickTimeMS;
	public float $avgBeginFrameTimeMS;
	public float $avgInputTimeMS;
	public float $avgRenderTimeMS;
	public float $avgEndFrameTimeMS;
	public float $avgRemainderTimePercent;
	public float $avgUnaccountedTimePercent;
	/**
	 * @var MemoryCategoryCounter[]
	 * @phpstan-var list<MemoryCategoryCounter>
	 */
	public array $memoryCategoryValues = [];
	/**
	 * @var EntityDiagnosticTimingInfo[]
	 * @phpstan-var list<EntityDiagnosticTimingInfo>
	 */
	public array $entityDiagnostics = [];
	/**
	 * @var SystemDiagnosticTimingInfo[]
	 * @phpstan-var list<SystemDiagnosticTimingInfo>
	 */
	public array $systemDiagnostics = [];
	/**
	 * @var WhiskerScopeDataSummary[]
	 * @phpstan-var list<WhiskerScopeDataSummary>
	 */
	public array $whiskerScopes = [];

	protected function decodePayload() : void
	{
		$this->avgFps = $this->getLFloat();
		$this->avgServerSimTickTimeMS = $this->getLFloat();
		$this->avgClientSimTickTimeMS = $this->getLFloat();
		$this->avgBeginFrameTimeMS = $this->getLFloat();
		$this->avgInputTimeMS = $this->getLFloat();
		$this->avgRenderTimeMS = $this->getLFloat();
		$this->avgEndFrameTimeMS = $this->getLFloat();
		$this->avgRemainderTimePercent = $this->getLFloat();
		$this->avgUnaccountedTimePercent = $this->getLFloat();

		$this->memoryCategoryValues = [];
		for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++){
			$this->memoryCategoryValues[] = MemoryCategoryCounter::read($this);
		}

		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->entityDiagnostics = [];
			for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++){
				$this->entityDiagnostics[] = EntityDiagnosticTimingInfo::read($this);
			}

			$this->systemDiagnostics = [];
			for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++){
				$this->systemDiagnostics[] = SystemDiagnosticTimingInfo::read($this);
			}

			if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
				$this->whiskerScopes = [];
				for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++) {
					$this->whiskerScopes[] = WhiskerScopeDataSummary::read($this);
				}
			}
		}
	}

	protected function encodePayload() : void
	{
		$this->putLFloat($this->avgFps);
		$this->putLFloat($this->avgServerSimTickTimeMS);
		$this->putLFloat($this->avgClientSimTickTimeMS);
		$this->putLFloat($this->avgBeginFrameTimeMS);
		$this->putLFloat($this->avgInputTimeMS);
		$this->putLFloat($this->avgRenderTimeMS);
		$this->putLFloat($this->avgEndFrameTimeMS);
		$this->putLFloat($this->avgRemainderTimePercent);
		$this->putLFloat($this->avgUnaccountedTimePercent);

		$this->putUnsignedVarInt(count($this->memoryCategoryValues));
		foreach($this->memoryCategoryValues as $value){
			$value->write($this);
		}

		if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_975) {
			$this->putUnsignedVarInt(count($this->entityDiagnostics));
			foreach($this->entityDiagnostics as $value){
				$value->write($this);
			}

			$this->putUnsignedVarInt(count($this->systemDiagnostics));
			foreach($this->systemDiagnostics as $value){
				$value->write($this);
			}

			if ($this->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
				$this->putUnsignedVarInt(count($this->whiskerScopes));
				foreach($this->whiskerScopes as $value){
					$value->write($this);
				}
			}
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleServerboundDiagnostics($this);
	}
}
