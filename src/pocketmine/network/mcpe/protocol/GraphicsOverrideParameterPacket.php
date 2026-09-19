<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\GraphicsOverrideParameterType;
use pocketmine\network\mcpe\protocol\types\ParameterKeyframeValue;
use function count;

class GraphicsOverrideParameterPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::GRAPHICS_OVERRIDE_PARAMETER_PACKET;

	/** @var ParameterKeyframeValue[] */
	private array $values = [];
	private float $unknownFloat;
	private Vector3 $unknownVector3;
	private string $biomeIdentifier;
	private ?string $playerIdentifier;
	private GraphicsOverrideParameterType $parameterType;
	private bool $reset;

	/**
	 * @generate-create-func
	 * @param ParameterKeyframeValue[] $values
	 */
	public static function create(
		array $values,
		?float $unknownFloat,
		?Vector3 $unknownVector3,
		string $biomeIdentifier,
		?string $playerIdentifier,
		GraphicsOverrideParameterType $parameterType,
		bool $reset,
	) : self{
		$result = new self();
		$result->values = $values;
		$result->unknownFloat = $unknownFloat;
		$result->unknownVector3 = $unknownVector3;
		$result->biomeIdentifier = $biomeIdentifier;
		$result->playerIdentifier = $playerIdentifier;
		$result->parameterType = $parameterType;
		$result->reset = $reset;
		return $result;
	}

	/**
	 * @return ParameterKeyframeValue[]
	 */
	public function getValues() : array{ return $this->values; }

	public function getUnknownFloat() : float{ return $this->unknownFloat; }

	public function getUnknownVector3() : Vector3{ return $this->unknownVector3; }

	public function getBiomeIdentifier() : string{ return $this->biomeIdentifier; }

	public function getPlayerIdentifier() : ?string{ return $this->playerIdentifier; }

	public function getParameterType() : GraphicsOverrideParameterType{ return $this->parameterType; }

	public function isReset() : bool{ return $this->reset; }

	protected function decodePayload() : void{
		for($i = 0; $i < $this->getUnsignedVarInt(); ++$i){
			$this->values[] = ParameterKeyframeValue::read($this);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_924) {
			$this->unknownFloat = $this->getLFloat();
			$this->unknownVector3 = $this->getVector3();
		}

		$this->biomeIdentifier = $this->getString();
		if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
			$this->playerIdentifier = $this->getOptional($this->getString(...));
		}

		$this->parameterType = GraphicsOverrideParameterType::fromPacket($this->getByte());
		$this->reset = $this->getBool();
	}

	protected function encodePayload() : void{
		$this->putUnsignedVarInt(count($this->values));
		foreach($this->values as $value){
			$value->write($this);
		}

		if ($this->protocol >= ProtocolInfo::PROTOCOL_924) {
			$this->putLFloat($this->unknownFloat);
			$this->putVector3($this->unknownVector3);
		}

		$this->putString($this->biomeIdentifier);
		if ($this->protocol >= ProtocolInfo::PROTOCOL_1001) {
			$this->putOptional($this->playerIdentifier, $this->putString(...));
		}

		$this->putByte($this->parameterType->value);
		$this->putBool($this->reset);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleGraphicsOverrideParameter($this);
	}
}
