<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\convert\ConstantTranslator;
use pocketmine\network\mcpe\convert\ConstantTranslatorException;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

use function count;

final class ItemStackRequest
{
	/**
	 * @param ItemStackRequestAction[] $actions
	 * @param string[]                 $filterStrings
	 * @phpstan-param list<string> $filterStrings
	 */
	public function __construct(
		private int $requestId,
		private array $actions,
		private array $filterStrings,
		private int $filterStringCause
	) {
	}

	public function getRequestId() : int
	{
		return $this->requestId;
	}

	/** @return ItemStackRequestAction[] */
	public function getActions() : array
	{
		return $this->actions;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getFilterStrings() : array
	{
		return $this->filterStrings;
	}

	public function getFilterStringCause() : int
	{
		return $this->filterStringCause;
	}

	private static function readAction(NetworkBinaryStream $in, int $typeId) : ItemStackRequestAction
	{
		$typeId = ConstantTranslator::getInstance()->fromNetworkId(ItemStackRequestActionType::class, $typeId, $in->getProtocol());
		return match($typeId) {
			TakeStackRequestAction::ID => TakeStackRequestAction::read($in),
			PlaceStackRequestAction::ID => PlaceStackRequestAction::read($in),
			SwapStackRequestAction::ID => SwapStackRequestAction::read($in),
			DropStackRequestAction::ID => DropStackRequestAction::read($in),
			DestroyStackRequestAction::ID => DestroyStackRequestAction::read($in),
			CraftingConsumeInputStackRequestAction::ID => CraftingConsumeInputStackRequestAction::read($in),
			CraftingCreateSpecificResultStackRequestAction::ID => CraftingCreateSpecificResultStackRequestAction::read($in),
			LabTableCombineStackRequestAction::ID => LabTableCombineStackRequestAction::read($in),
			BeaconPaymentStackRequestAction::ID => BeaconPaymentStackRequestAction::read($in),
			MineBlockStackRequestAction::ID => MineBlockStackRequestAction::read($in),
			CraftRecipeStackRequestAction::ID => CraftRecipeStackRequestAction::read($in),
			CraftRecipeAutoStackRequestAction::ID => CraftRecipeAutoStackRequestAction::read($in),
			CreativeCreateStackRequestAction::ID => CreativeCreateStackRequestAction::read($in),
			CraftRecipeOptionalStackRequestAction::ID => CraftRecipeOptionalStackRequestAction::read($in),
			GrindstoneStackRequestAction::ID => GrindstoneStackRequestAction::read($in),
			LoomStackRequestAction::ID => LoomStackRequestAction::read($in),
			DeprecatedCraftingNonImplementedStackRequestAction::ID => DeprecatedCraftingNonImplementedStackRequestAction::read($in),
			DeprecatedCraftingResultsStackRequestAction::ID => DeprecatedCraftingResultsStackRequestAction::read($in),
			default => throw new PacketDecodeException("Unhandled item stack request action type $typeId"),
		};
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$requestId = $in->readItemStackRequestId();
		$actions = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			$typeId = $in->getProtocol() >= ProtocolInfo::PROTOCOL_2193 ? $in->getUnsignedVarInt() : $in->getByte();
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
				$in->getByte();
			}
			$actions[] = self::readAction($in, $typeId);
		}
		$filterStrings = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			$filterStrings[] = $in->getString();
		}
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_557) {
			$filterStringCause = $in->getLInt();
		}
		return new self($requestId, $actions, $filterStrings, $filterStringCause ?? 0);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->writeItemStackRequestId($this->requestId);
		$out->putUnsignedVarInt(count($this->actions));
		foreach ($this->actions as $action) {
			try {
				$typeId = ConstantTranslator::getInstance()->toNetworkId(ItemStackRequestActionType::class, $action->getTypeId(), $out->getProtocol());
				if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
					$out->putUnsignedVarInt($typeId);
				}
				$out->putByte($typeId);
				$action->write($out);
			} catch (ConstantTranslatorException $exception) {
			}
		}
		$out->putUnsignedVarInt(count($this->filterStrings));
		foreach ($this->filterStrings as $string) {
			$out->putString($string);
		}
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_557) {
			$out->putLInt($this->filterStringCause);
		}
	}
}
