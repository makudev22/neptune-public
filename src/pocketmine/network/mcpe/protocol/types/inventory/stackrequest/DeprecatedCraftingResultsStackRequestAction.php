<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\item\Item;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use function count;

/**
 * Not clear what this is needed for, but it is very clearly marked as deprecated, so hopefully it'll go away before I
 * have to write a proper description for it.
 */
final class DeprecatedCraftingResultsStackRequestAction extends ItemStackRequestAction
{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_RESULTS_DEPRECATED_ASK_TY_LAING;

	/**
	 * @param ItemStack[] $results
	 */
	public function __construct(
		private array $results,
		private int $iterations
	) {
	}

	/** @return Item[] */
	public function getResults() : array
	{
		return $this->results;
	}

	public function getIterations() : int
	{
		return $this->iterations;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$results = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
				$descriptorType = $in->getUnsignedVarInt();
				$in->getByte();
				if ($descriptorType === 1) {
					$in->getString();
					$in->getVarInt();
				}
				$in->getLShort();
				$in->getUnsignedVarInt();
				$in->getString();
			} else {
				$results[] = $in->getItemStackWithoutStackId();
			}
		}
		$iterations = $in->getByte();
		return new self($results, $iterations);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->results));
		foreach ($this->results as $result) {
			$out->putItemStackWithoutStackId($result);
		}
		$out->putByte($this->iterations);
	}
}
