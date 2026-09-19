<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

final class CreativeGroupEntry
{
	public function __construct(
		private int $categoryId,
		private string $categoryName,
		private ItemStack $icon
	) {
	}

	public function getCategoryId() : int
	{
		return $this->categoryId;
	}

	public function getCategoryName() : string
	{
		return $this->categoryName;
	}

	public function getIcon() : ItemStack
	{
		return $this->icon;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$categoryId = $in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 ? $in->getByte() : $in->getLInt();
		$categoryName = $in->getString();
		$icon = $in->getItemStackWithoutStackId();
		return new self($categoryId, $categoryName, $icon);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putByte($this->categoryId);
		} else {
			$out->putLInt($this->categoryId);
		}
		$out->putString($this->categoryName);
		$out->putItemStackWithoutStackId($this->icon);
	}
}
