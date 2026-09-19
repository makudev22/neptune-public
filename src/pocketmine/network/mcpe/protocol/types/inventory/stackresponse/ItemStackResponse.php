<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\inventory\stackresponse;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

use function count;

final class ItemStackResponse
{
	public const RESULT_OK = 0;
	public const RESULT_ERROR = 1;
	//TODO: there are a ton more possible result types but we don't need them yet and they are wayyyyyy too many for me
	//to waste my time on right now...

	/**
	 * @param ItemStackResponseContainerInfo[] $containerInfos
	 */
	public function __construct(
		private int $result,
		private int $requestId,
		private array $containerInfos = []
	) {
		if ($this->result !== self::RESULT_OK && count($this->containerInfos) !== 0) {
			throw new \InvalidArgumentException("Container infos must be empty if rejecting the request");
		}
	}

	public function getResult() : int
	{
		return $this->result;
	}

	public function getRequestId() : int
	{
		return $this->requestId;
	}

	/** @return ItemStackResponseContainerInfo[] */
	public function getContainerInfos() : array
	{
		return $this->containerInfos;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$result = $in->getByte();
		$requestId = $in->readItemStackRequestId();
		$containerInfos = [];
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$containerInfos = $in->getOptional(function() use ($in) : array {
				$infos = [];
				for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
					$infos[] = ItemStackResponseContainerInfo::read($in);
				}
				return $infos;
			});
		} elseif($result === self::RESULT_OK) {
			for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
				$containerInfos[] = ItemStackResponseContainerInfo::read($in);
			}
		}
		return new self($result, $requestId, $containerInfos ?? []);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putByte($this->result);
		$out->writeItemStackRequestId($this->requestId);
		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_2193) {
			$out->putOptional($this->result === self::RESULT_OK && count($this->containerInfos) > 0 ? $this->containerInfos : null, function(array $containerInfos) use ($out) : void {
				$out->putUnsignedVarInt(count($containerInfos));
				foreach ($containerInfos as $containerInfo) {
					$containerInfo->write($out);
				}
			});
		} elseif($this->result === self::RESULT_OK) {
			$out->putUnsignedVarInt(count($this->containerInfos));
			foreach ($this->containerInfos as $containerInfo) {
				$containerInfo->write($out);
			}
		}
	}
}
