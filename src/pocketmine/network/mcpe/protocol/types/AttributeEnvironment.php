<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class AttributeEnvironment{

	public function __construct(
		public string $attributeName,
		public ?AttributeData $fromAttribute,
		public AttributeData $attribute,
		public ?AttributeData $toAttribute,
		public int $currentTransitionTicks,
		public int $totalTransitionTicks,
		public string $easeType,
		public int $localTransitionTicks,
		public bool $noiseTransition
	){}

	public static function read(NetworkBinaryStream $in) : self{
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$localTransitionTicks = $in->getLInt();
			$noiseTransition = $in->getBool();
		}

		return new self(
			$in->getString(),
			$in->getOptional(fn () => AttributeData::read($in)),
			AttributeData::read($in),
			$in->getOptional(fn () => AttributeData::read($in)),
			$in->getLInt(),
			$in->getLInt(),
			$in->getString(),
			$localTransitionTicks ?? 0,
			$noiseTransition ?? false
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putString($this->attributeName);
		$out->putOptional($this->fromAttribute, fn (AttributeData $v) => $v->write($out));
		$this->attribute->write($out);
		$out->putOptional($this->toAttribute, fn (AttributeData $v) => $v->write($out));
		$out->putLInt($this->currentTransitionTicks);
		$out->putLInt($this->totalTransitionTicks);
		$out->putString($this->easeType);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_1001) {
			$out->putLInt($this->localTransitionTicks);
			$out->putBool($this->noiseTransition);
		}
	}
}
