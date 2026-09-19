<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

use function count;

class UnlockedRecipesPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::UNLOCKED_RECIPES_PACKET;

	private bool $newRecipes;
	private int $type;
	/** @var string[] */
	private array $recipes;

	public const TYPE_EMPTY = 0;
	public const TYPE_INITIALLY_UNLOCKED = 1;
	public const TYPE_NEWLY_UNLOCKED = 2;
	public const TYPE_REMOVE = 3;
	public const TYPE_REMOVE_ALL = 4;

	/**
	 * @generate-create-func
	 * @param string[] $recipes
	 */
	public static function create(bool $newRecipes, int $type, array $recipes) : self
	{
		$result = new self();
		$result->newRecipes = $newRecipes;
		$result->type = $type;
		$result->recipes = $recipes;
		return $result;
	}

	public function isNewRecipes() : bool
	{
		return $this->newRecipes;
	}

	public function getType() : int
	{
		return $this->type;
	}

	/**
	 * @return string[]
	 */
	public function getRecipes() : array
	{
		return $this->recipes;
	}

	protected function decodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_589) {
			$this->type = $this->getLInt();
		} else {
			$this->newRecipes = $this->getBool();
		}
		$this->recipes = [];
		for ($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; $i++) {
			$this->recipes[] = $this->getString();
		}
	}

	protected function encodePayload() : void
	{
		if ($this->protocol >= ProtocolInfo::PROTOCOL_589) {
			$this->putLInt($this->type);
		} else {
			$this->putBool($this->newRecipes);
		}
		$this->putUnsignedVarInt(count($this->recipes));
		foreach ($this->recipes as $recipe) {
			$this->putString($recipe);
		}
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleUnlockedRecipes($this);
	}
}
