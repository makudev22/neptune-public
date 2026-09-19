<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class FurnaceRecipe extends RecipeWithTypeId{
	public function __construct(
		int $typeId,
		private int $inputId,
		private ?int $inputMeta,
		private ItemStack $result,
		private string $blockName
	){
		parent::__construct($typeId);
	}

	public function getInputId() : int{
		return $this->inputId;
	}

	public function getInputMeta() : ?int{
		return $this->inputMeta;
	}

	public function getResult() : ItemStack{
		return $this->result;
	}

	public function getBlockName() : string{
		return $this->blockName;
	}

	public static function decode(int $typeId, NetworkBinaryStream $in) : self{
		$inputId = $in->getVarInt();
		$inputData = null;
		if($typeId === CraftingDataPacket::ENTRY_FURNACE_DATA){
			$inputData = $in->getVarInt();
		}
		$output = $in->getItemStackWithoutStackId();
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$block = $in->getString();
		}

		return new self($typeId, $inputId, $inputData, $output, $block ?? FurnaceRecipeBlockName::FURNACE);
	}

	public function encode(NetworkBinaryStream $out) : void{
		$out->putVarInt($this->inputId);
		if($this->getTypeId() === CraftingDataPacket::ENTRY_FURNACE_DATA){
			$out->putVarInt($this->inputMeta);
		}
		$out->putItemStackWithoutStackId($this->result);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_407) {
			$out->putString($this->blockName);
		}
	}
}
