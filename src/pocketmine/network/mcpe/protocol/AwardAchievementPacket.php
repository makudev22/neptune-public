<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;

class AwardAchievementPacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::AWARD_ACHIEVEMENT_PACKET;

	private int $achievementId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $achievementId) : self
	{
		$result = new self();
		$result->achievementId = $achievementId;
		return $result;
	}

	public function getAchievementId() : int
	{
		return $this->achievementId;
	}

	protected function decodePayload() : void
	{
		$this->achievementId = $this->getLInt();
	}

	protected function encodePayload() : void
	{
		$this->putLInt($this->achievementId);
	}

	public function mustBeDecoded() : bool
	{
		return false;
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleAwardAchievement($this);
	}
}
