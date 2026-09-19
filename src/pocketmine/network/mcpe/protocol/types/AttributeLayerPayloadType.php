<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

final class AttributeLayerPayloadType{
	public const UPDATE_LAYERS = 0;
	public const UPDATE_SETTINGS = 1;
	public const UPDATE_ENVIRONMENT = 2;
	public const REMOVE_ENVIRONMENT = 3;
}
