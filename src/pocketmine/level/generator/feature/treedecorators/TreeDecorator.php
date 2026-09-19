<?php


declare(strict_types=1);

namespace pocketmine\level\generator\feature\treedecorators;

abstract class TreeDecorator {

	abstract protected function type() : TreeDecoratorType;

	abstract public function place(TreeDecoratorContext $context) : void;

}
