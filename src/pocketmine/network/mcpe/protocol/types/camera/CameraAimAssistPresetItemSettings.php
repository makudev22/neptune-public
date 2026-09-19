<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

final class CameraAimAssistPresetItemSettings
{
	public function __construct(
		private string $itemIdentifier,
		private string $categoryName,
	) {
	}

	public function getItemIdentifier() : string
	{
		return $this->itemIdentifier;
	}

	public function getCategoryName() : string
	{
		return $this->categoryName;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$itemIdentifier = $in->getString();
		$categoryName = $in->getString();
		return new self(
			$itemIdentifier,
			$categoryName
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putString($this->itemIdentifier);
		$out->putString($this->categoryName);
	}
}
