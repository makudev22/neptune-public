<?php


declare(strict_types=1);

namespace pocketmine\permission;

use InvalidStateException;

class PermissionAttachmentInfo
{
	/** @var Permissible */
	private $permissible;

	/** @var string */
	private $permission;

	/** @var PermissionAttachment|null */
	private $attachment;

	/** @var bool */
	private $value;

	/**
	 * @throws InvalidStateException
	 */
	public function __construct(Permissible $permissible, string $permission, PermissionAttachment $attachment = null, bool $value)
	{
		$this->permissible = $permissible;
		$this->permission = $permission;
		$this->attachment = $attachment;
		$this->value = $value;
	}

	public function getPermissible() : Permissible
	{
		return $this->permissible;
	}

	public function getPermission() : string
	{
		return $this->permission;
	}

	/**
	 * @return PermissionAttachment|null
	 */
	public function getAttachment()
	{
		return $this->attachment;
	}

	public function getValue() : bool
	{
		return $this->value;
	}
}
