<?php


declare(strict_types=1);

namespace raklib\protocol;

class ACK extends AcknowledgePacket
{
	/** @var int */
	public static $ID = 0xc0;
}
