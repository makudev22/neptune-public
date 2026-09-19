<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\NetworkBinaryStream;

/**
 * @see PlayerArmorDamagePacket
 */
final class ArmorSlotAndDamagePair{

	public function __construct(
		private ArmorSlot $slot,
		private int $damage
	){}

	public function getSlot() : ArmorSlot{ return $this->slot; }

	public function getDamage() : int{ return $this->damage; }

	public static function read(NetworkBinaryStream $in) : self{
		$slot = ArmorSlot::fromPacket($in->getByte());
		$damage = $in->getLShort();

		return new self(
			$slot,
			$damage
		);
	}

	public function write(NetworkBinaryStream $out) : void{
		$out->putByte($this->slot->value);
		$out->putLShort($this->damage);
	}
}
