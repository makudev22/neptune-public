<?php


declare(strict_types=1);

// composer autoload doesn't use require_once and also pmmpthread can inherit things
if (defined('pocketmine\_GLOBAL_CONSTANTS_INCLUDED')) {
	return;
}
define('pocketmine\_GLOBAL_CONSTANTS_INCLUDED', true);

const UINT8_MAX = 0xff;
const INT8_MIN = -0x7f - 1;
const INT8_MAX = 0x7f;

const UINT16_MAX = 0xffff;
const INT16_MIN = -0x7fff - 1;
const INT16_MAX = 0x7fff;

const UINT32_MAX = 0xffffffff;
const INT32_MIN = -0x7fffffff - 1;
const INT32_MAX = 0x7fffffff;

const UINT64_MAX = 0xffffffffffffffff;
const INT64_MIN = -0x7fffffffffffffff - 1;
const INT64_MAX = 0x7fffffffffffffff;
