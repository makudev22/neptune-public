<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreChange;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreOperation;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreOperationType;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreRemoval;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreUpdate;
use function count;

class ClientboundDataStorePacket extends DataPacket
{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_STORE_PACKET;

	/**
	 * @var DataStoreOperation[]
	 * @phpstan-var list<DataStoreOperation>
	 */
	public array $values = [];

	/**
	 * @generate-create-func
	 * @param DataStoreOperation[] $values
	 * @phpstan-param list<DataStoreOperation> $values
	 */
	public static function create(array $values) : self{
		$result = new self();
		$result->values = $values;
		return $result;
	}

	protected function decodePayload() : void{
		$this->values = [];
		for($i = 0, $len = $this->getVarInt(); $i < $len; ++$i){
			$this->values[] = match(DataStoreOperationType::fromPacket($this->getVarInt())){
				DataStoreOperationType::UPDATE => DataStoreUpdate::read($this),
				DataStoreOperationType::CHANGE => DataStoreChange::read($this),
				DataStoreOperationType::REMOVAL => DataStoreRemoval::read($this),
			};
		}
	}

	protected function encodePayload() : void{
		$this->putVarInt(count($this->values));
		foreach($this->values as $value){
			$this->putVarInt($value->getTypeId()->value);
			$value->write($this);
		}
	}

	public function handle(NetworkSession $session) : bool
	{
		return $session->handleClientboundDataStore($this);
	}
}
