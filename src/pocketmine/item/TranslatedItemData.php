<?php


declare(strict_types=1);

namespace pocketmine\item;

class TranslatedItemData {

	public static function fromItem(Item $item) : TranslatedItemData{
		return new self(
			$item->getId(),
			$item->getDamage(),
			$item->getName(),
		);
	}

	public function __construct(
		private int $id,
		private ?int $meta,
		private string $name = "",
	){}

	public function getId() : int {
		return $this->id;
	}

	public function getMeta() : ?int {
		return $this->meta;
	}

	public function getName() : string {
		return $this->name;
	}

	public function hasName() : bool{
		return $this->name !== "";
	}
}
