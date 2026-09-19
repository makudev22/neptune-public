<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\NetworkBinaryStream;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use function count;

final class CameraAimAssistCategoryPriorities
{
	/**
	 * @param CameraAimAssistCategoryEntityPriority[]|CameraAimAssistCategoryPriority[] $entities
	 * @param CameraAimAssistCategoryPriority[]                                         $blocks
	 * @param CameraAimAssistCategoryPriority[]|int[]                                   $blockTags
	 * @param CameraAimAssistCategoryPriority[]                                         $entityTypeFamilies
	 */
	public function __construct(
		private array $entities,
		private array $blocks,
		private array $blockTags,
		private array $entityTypeFamilies,
		private ?int $defaultEntityPriority,
		private ?int $defaultBlockPriority
	) {
	}

	/**
	 * @return CameraAimAssistCategoryEntityPriority[]|CameraAimAssistCategoryPriority[]
	 */
	public function getEntities() : array
	{
		return $this->entities;
	}

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getBlocks() : array
	{
		return $this->blocks;
	}

	/**
	 * @return CameraAimAssistCategoryPriority[]|int[]
	 */
	public function getBlockTags() : array{ return $this->blockTags; }

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getEntityTypeFamilies() : array
	{
		return $this->entityTypeFamilies;
	}

	public function getDefaultEntityPriority() : ?int
	{
		return $this->defaultEntityPriority;
	}

	public function getDefaultBlockPriority() : ?int
	{
		return $this->defaultBlockPriority;
	}

	public static function read(NetworkBinaryStream $in) : self
	{
		$entities = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
				$entities[] = CameraAimAssistCategoryPriority::read($in);
			} else {
				$entities[] = CameraAimAssistCategoryEntityPriority::read($in);
			}
		}

		$blocks = [];
		for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
			$blocks[] = CameraAimAssistCategoryPriority::read($in);
		}

		$blockTags = [];
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_897) {
			for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
				if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
					$blockTags[] = CameraAimAssistCategoryPriority::read($in);
				} else {
					$blockTags[] = $in->getUnsignedVarInt();
				}
			}
		}

		$entityTypeFamilies = [];
		if ($in->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
			for ($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i) {
				$entityTypeFamilies[] = CameraAimAssistCategoryPriority::read($in);
			}
		}

		$defaultEntityPriority = $in->getOptional(fn () => $in->getLInt());
		$defaultBlockPriority = $in->getOptional(fn () => $in->getLInt());
		return new self(
			$entities,
			$blocks,
			$blockTags,
			$entityTypeFamilies,
			$defaultEntityPriority,
			$defaultBlockPriority
		);
	}

	public function write(NetworkBinaryStream $out) : void
	{
		$out->putUnsignedVarInt(count($this->entities));
		foreach ($this->entities as $entity) {
			$entity->write($out);
		}

		$out->putUnsignedVarInt(count($this->blocks));
		foreach ($this->blocks as $block) {
			$block->write($out);
		}

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_897) {
			$out->putUnsignedVarInt(count($this->blockTags));
			foreach($this->blockTags as $tag){
				if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
					$tag->write($out);
				} else {
					$out->putUnsignedVarInt($tag);
				}
			}
		}

		if ($out->getProtocol() >= ProtocolInfo::PROTOCOL_924) {
			$out->putUnsignedVarInt(count($this->entityTypeFamilies));
			foreach ($this->entityTypeFamilies as $entityTypeFamily) {
				$entityTypeFamily->write($out);
			}
		}

		$out->putOptional($this->defaultEntityPriority, fn (int $v) => $out->putLInt($v));
		$out->putOptional($this->defaultBlockPriority, fn (int $v) => $out->putLInt($v));
	}
}
