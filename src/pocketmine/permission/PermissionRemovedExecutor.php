<?php


declare(strict_types=1);

namespace pocketmine\permission;

interface PermissionRemovedExecutor
{
	/**
	 * @return void
	 */
	public function attachmentRemoved(PermissionAttachment $attachment);
}
