<?php


declare(strict_types=1);

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;

interface Permissible extends ServerOperator
{
	/**
	 * Checks if this instance has a permission overridden
	 *
	 * @param string|Permission $name
	 */
	public function isPermissionSet($name) : bool;

	/**
	 * Returns the permission value if overridden, or the default value if not
	 */
	public function hasPermission(Permission|string $name) : bool;

	public function addAttachment(Plugin $plugin, string $name = null, bool $value = null) : PermissionAttachment;

	/**
	 * @return void
	 */
	public function removeAttachment(PermissionAttachment $attachment);

	/**
	 * @return void
	 */
	public function recalculatePermissions();

	/**
	 * @return PermissionAttachmentInfo[]
	 */
	public function getEffectivePermissions() : array;

}
