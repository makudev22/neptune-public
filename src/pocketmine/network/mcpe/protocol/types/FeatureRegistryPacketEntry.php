<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class FeatureRegistryPacketEntry
{
	public function __construct(
		private string $featureName,
		private string $featureJson
	) {
	}

	public function getFeatureName() : string
	{
		return $this->featureName;
	}

	public function getFeatureJson() : string
	{
		return $this->featureJson;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$featureName = $in->getString();
		$featureJson = $in->getString();

		return new self($featureName, $featureJson);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->featureName);
		$out->putString($this->featureJson);
	}
}
