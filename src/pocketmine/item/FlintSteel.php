<?php


declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\level\Level;
use pocketmine\level\sound\FlintSteelSound;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\Player;

use pocketmine\Server;
use function count;

class FlintSteel extends Tool
{
	public function __construct(int $meta = 0)
	{
		parent::__construct(self::FLINT_STEEL, $meta, "Flint and Steel");
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool
	{
		if ($blockReplace->getId() === self::AIR) {
			$fire = BlockFactory::get((($did = $blockReplace->getSide(Facing::DOWN)->getId()) == BlockIds::SOUL_SAND || $did == BlockIds::SOUL_SOIL) ? BlockIds::SOUL_FIRE : BlockIds::FIRE);
			$level = $player->getLevel();
			$level->setBlock($blockReplace, $fire, true);
			$level->addSound(new FlintSteelSound($blockReplace->add(0.5, 0.5, 0.5)));

			$this->applyDamage(1);

			if(Server::getInstance()->isAllowNether()){
				if($blockClicked->getId() === BlockIds::OBSIDIAN || $blockReplace->getId() === BlockIds::FIRE){
					if($this->tryCreateNetherPortal($blockReplace, $level)){
						return true;
					}
				}
			}

			return true;
		}

		return false;
	}

	private function tryCreateNetherPortal(Block $fireBlock, Level $level) : bool{
	foreach([Facing::WEST, Facing::NORTH] as $direction){
	  $portalBlocks = $this->findPortalFrame($fireBlock->asVector3(), $direction, $level);
	  if($portalBlocks !== null && count($portalBlocks) > 0){
		foreach($portalBlocks as $portalPos){
		  $level->setBlock($portalPos, BlockFactory::get(BlockIds::PORTAL));
		}
		return true;
	  }
	}
	return false;
  }

  /**
   * Finds and validates a nether portal frame
   *
   * @param Vector3 $firePos   The fire block position (inside portal)
   * @param int     $direction The direction to check (WEST for X-axis, NORTH for Z-axis)
   * @param Level   $level     The level
   * @return array|null Array of Vector3 positions for portal blocks, or null if invalid
   */
  private function findPortalFrame(Vector3 $firePos, int $direction, Level $level) : ?array{
	$corner = $this->findBottomCorner($firePos, $direction, $level);

	if($corner === null){
	  return null;
	}

	if($direction === Facing::NORTH){
	  $widthVec = new Vector3(1, 0, 0);
	  $heightVec = new Vector3(0, 1, 0);
	}else{
	  $widthVec = new Vector3(0, 0, 1);
	  $heightVec = new Vector3(0, 1, 0);
	}

	$width = 0;
	for($w = 0; $w <= 23; $w++){
	  $checkPos = $corner->addVector($widthVec->multiply($w));
	  $checkBlock = $level->getBlock($checkPos);

	  if($checkBlock->getId() === BlockIds::OBSIDIAN){
		$width = $w + 1;
	  }else{
		break;
	  }
	}

	$height = 0;
	for($h = 0; $h <= 23; $h++){
	  $checkPos = $corner->addVector($heightVec->multiply($h));
	  $checkBlock = $level->getBlock($checkPos);

	  if($checkBlock->getId() === BlockIds::OBSIDIAN){
		$height = $h + 1;
	  }else{
		break;
	  }
	}

	// Validate dimensions: minimum 4 wide (2 obsidian + 2 air) and 5 tall (3 obsidian + 2 air)
	// Maximum 23x23
	if($width < 4 || $width > 23 || $height < 5 || $height > 23){
	  return null;
	}

	if(!$this->validatePortalFrame($corner, $width, $height, $widthVec, $heightVec, $level)){
	  return null;
	}

	$portalBlocks = [];
	for($x = 1; $x < $width - 1; $x++){
	  for($y = 1; $y < $height - 1; $y++){
		$portalPos = $corner->addVector($widthVec->multiply($x))->addVector($heightVec->multiply($y));
		$portalBlocks[] = $portalPos;
	  }
	}

	return $portalBlocks;
  }

  /**
   * Finds the bottom-left corner of a portal frame
   *
   * @param Vector3 $start     Starting position (fire block)
   * @param int     $direction Portal direction
   * @param Level   $level     The level
   * @return Vector3|null The corner position or null
   */
  private function findBottomCorner(Vector3 $start, int $direction, Level $level) : ?Vector3{
	$pos = $start->floor();

	$foundBottom = false;
	for($i = 0; $i < 23; $i++){
	  $below = $level->getBlock($pos->subtract(0, 1, 0));
	  if($below->getId() === BlockIds::OBSIDIAN){
		$pos = $pos->subtract(0, 1, 0);
		$foundBottom = true;
	  }elseif($foundBottom){
		break;
	  }else{
		$pos = $pos->subtract(0, 1, 0);
	  }
	}

	if($direction === Facing::NORTH){
	  $foundLeft = false;
	  for($i = 0; $i < 23; $i++){
		$left = $level->getBlock($pos->subtract(1, 0, 0));
		if($left->getId() === BlockIds::OBSIDIAN){
		  $pos = $pos->subtract(1, 0, 0);
		  $foundLeft = true;
		}elseif($foundLeft){
		  break;
		}else{
		  $pos = $pos->subtract(1, 0, 0);
		}
	  }
	}else{
	  $foundLeft = false;
	  for($i = 0; $i < 23; $i++){
		$left = $level->getBlock($pos->subtract(0, 0, 1));
		if($left->getId() === BlockIds::OBSIDIAN){
		  $pos = $pos->subtract(0, 0, 1);
		  $foundLeft = true;
		}elseif($foundLeft){
		  break;
		}else{
		  $pos = $pos->subtract(0, 0, 1);
		}
	  }
	}

	if($level->getBlock($pos)->getId() === BlockIds::OBSIDIAN){
	  return $pos;
	}

	return null;
  }

  /**
   * Validates that the portal frame is complete and correct
   *
   * @param Vector3 $corner    Bottom-left corner
   * @param int     $width     Frame width
   * @param int     $height    Frame height
   * @param Vector3 $widthVec  Width direction vector
   * @param Vector3 $heightVec Height direction vector
   * @param Level   $level     The level
   * @return bool True if valid frame
   */
	private function validatePortalFrame(Vector3 $corner, int $width, int $height, Vector3 $widthVec, Vector3 $heightVec, Level $level) : bool{
	for($i = 0; $i < $width; $i++){
	  $pos = $corner->addVector($widthVec->multiply($i));
	  if($level->getBlock($pos)->getId() !== BlockIds::OBSIDIAN){
		return false;
	  }
	}

	for($i = 0; $i < $width; $i++){
	  $pos = $corner->addVector($widthVec->multiply($i))->addVector($heightVec->multiply($height - 1));
	  if($level->getBlock($pos)->getId() !== BlockIds::OBSIDIAN){
		return false;
	  }
	}

	for($i = 0; $i < $height; $i++){
	  $pos = $corner->addVector($heightVec->multiply($i));
	  if($level->getBlock($pos)->getId() !== BlockIds::OBSIDIAN){
		return false;
	  }
	}

	for($i = 0; $i < $height; $i++){
	  $pos = $corner->addVector($widthVec->multiply($width - 1))->addVector($heightVec->multiply($i));
	  if($level->getBlock($pos)->getId() !== BlockIds::OBSIDIAN){
		return false;
	  }
	}

	for($x = 1; $x < $width - 1; $x++){
	  for($y = 1; $y < $height - 1; $y++){
		$pos = $corner->addVector($widthVec->multiply($x))->addVector($heightVec->multiply($y));
		$blockId = $level->getBlock($pos)->getId();
		if($blockId !== BlockIds::AIR && $blockId !== BlockIds::FIRE && $blockId !== BlockIds::SOUL_FIRE){
		  return false;
		}
	  }
	}

	return true;
  }

	public function getMaxDurability() : int
	{
		return 65;
	}
}
