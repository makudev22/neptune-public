<?php


declare(strict_types=1);

namespace pocketmine\utils\valueproviders;

enum IntProviderType {
	case CONSTANT;
	case UNIFORM;
	case BIASED_TO_BOTTOM;
	case CLAMPED;
	case WEIGHTED_LIST;
	case CLAMPED_NORMAL;
}
