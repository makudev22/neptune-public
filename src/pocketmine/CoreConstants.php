<?php


declare(strict_types=1);

namespace pocketmine;

use function define;
use function defined;
use function dirname;

// composer autoload doesn't use require_once and also pmmpthread can inherit things
if (defined('pocketmine\_CORE_CONSTANTS_INCLUDED')) {
	return;
}
define('pocketmine\_CORE_CONSTANTS_INCLUDED', true);

define('pocketmine\PATH', dirname(__DIR__, 2) . '/');
define('pocketmine\RESOURCE_PATH', __DIR__ . '/resources/');
define('pocketmine\BEDROCK_DATA_PATH', __DIR__ . '/resources/vanilla/');
define('pocketmine\COMPOSER_AUTOLOADER_PATH', dirname(__DIR__, 2) . '/vendor/autoload.php');
define('pocketmine\BEDROCK_BLOCK_UPGRADE_SCHEMA_PATH', dirname(__DIR__, 2) . '/vendor/pocketmine/bedrock-block-upgrade-schema/');
