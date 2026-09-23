<?php


declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\skin;

use Ahc\Json\Comment;
use InvalidArgumentException;
use pocketmine\entity\Skin;
use pocketmine\utils\Color;
use pocketmine\utils\UUID;
use SplFixedArray;

use function file_get_contents;
use function json_decode;
use function json_encode;
use function json_last_error_msg;

use const pocketmine\BEDROCK_DATA_PATH;
use const pocketmine\RESOURCE_PATH;

class SerializedSkin
{
	public const GEOMETRY_CUSTOM = "geometry.humanoid.custom";
	public const GEOMETRY_CUSTOM_SLIM = "geometry.humanoid.customSlim";

	public const ARM_SIZE_SLIM = "slim";
	public const ARM_SIZE_WIDE = "wide";

	/** @var string[]|null */
	public static ?array $defaultSkins = null;

	/** @var array[]|null */
	public static ?array $skinIdToBedrockMap = null;

	public static ?string $defaultGeometryData = null;

	public static function init() : void
	{
		$skinIdMap = json_decode(file_get_contents(BEDROCK_DATA_PATH . "/skins/pw10_skins/skin_id_map.json"), true);
		foreach ($skinIdMap as $skinId => $item) {
			$geometryData = file_get_contents(BEDROCK_DATA_PATH . "/skins/pw10_skins/geometry/" . $item["geometry"] . ".json");

			$data = [
				"geometry" => $item["geometry"],
				"geometryData" => $geometryData,
			];
			if (isset($item["cape"])) {
				$capeData = file_get_contents(BEDROCK_DATA_PATH . "/skins/pw10_skins/capes/" . $item["cape"] . ".skindata");

				$data["cape"] = $item["cape"];
				$data["capeData"] = $capeData;
			}
			self::$skinIdToBedrockMap[$skinId] = $data;
		}

		self::$defaultGeometryData = file_get_contents(BEDROCK_DATA_PATH . "/skins/geometry_data.json");
	}

	public static function lazyInit() : void
	{
		if (self::$skinIdToBedrockMap === null) {
			self::init();
		}
	}

	public static function isSkinIdPE(string $skinId) : bool
	{
		self::lazyInit();

		return isset(self::$skinIdToBedrockMap[$skinId]);
	}

	public static function fromSkin(Skin $skin) : SerializedSkin
	{
		self::lazyInit();

		$mapping = self::$skinIdToBedrockMap[$skin->getSkinId()] ?? null;
		if ($mapping === null) {
			return new self(
				$skin->getSkinId(),
				"",
				SkinImage::fromLegacy($skin->getSkinData()),
				"",
				$skin->getCapeData() === "" ? new SkinImage(0, 0, "") : SkinImage::fromLegacy($skin->getCapeData()),
				json_encode(["geometry" => ["default" => ($skin->getGeometryName() === "" ? self::GEOMETRY_CUSTOM : $skin->getGeometryName())]]),
				self::updateGeometry($skin->getGeometryData(), $skin->getGeometryName()),
				"0.0.0",
				"",
				[],
				false,
				false,
				false,
				null,
				self::ARM_SIZE_WIDE,
				new Color(0, 0, 0),
				SplFixedArray::fromArray([]),
				SplFixedArray::fromArray([]),
				true,
				true
			);
		} else {
			return new self(
				$skin->getSkinId(),
				"",
				SkinImage::fromLegacy($skin->getSkinData()),
				$mapping["cape"] ?? "",
				($mapping["capeData"] ?? $skin->getCapeData()) === "" ? new SkinImage(0, 0, "") : SkinImage::fromLegacy($mapping["capeData"] ?? $skin->getCapeData()),
				json_encode(["geometry" => ["default" => $mapping["geometry"]]]),
				$mapping["geometryData"],
				"0.0.0",
				"",
				[],
				false,
				false,
				false,
				null,
				self::ARM_SIZE_WIDE,
				new Color(0, 0, 0),
				SplFixedArray::fromArray([]),
				SplFixedArray::fromArray([]),
				true,
				true
			);
		}
	}

	public static function updateGeometry(string $skinGeometryData, string $skinGeometryName) : string
	{
		if ($skinGeometryData === "" || $skinGeometryName === "" || $skinGeometryName === self::GEOMETRY_CUSTOM || $skinGeometryName === self::GEOMETRY_CUSTOM_SLIM) {
			return self::$defaultGeometryData ?? "";
		}

		return $skinGeometryData;
	}

	/** @var string */
	protected $skinId;
	/** @var string */
	protected $playFabId;
	/** @var SkinImage */
	protected $skinImage;
	/** @var string */
	protected $capeId;
	/** @var SkinImage */
	protected $capeImage;
	/** @var string */
	protected $resourcePatch;
	/** @var string */
	protected $geometryData;
	/** @var string */
	protected $geometryDataEngineVersion;
	/** @var string */
	protected $animationData;
	/** @var SkinAnimation[] */
	protected $animationFrames;
	/** @var bool */
	protected $premiumSkin;
	/** @var bool */
	protected $personaSkin;
	/** @var bool */
	protected $capeOnClassicSkin;
	/** @var string|null */
	protected $fullSkinId;
	/** @var string */
	protected $armSize;
	/** @var Color */
	protected $skinColor;
	/** @var SplFixedArray */
	protected $personaPieces;
	/** @var SplFixedArray */
	protected $pieceTintColors;
	/** @var bool */
	protected $isTrusted = true;
	/** @var bool */
	protected $isPrimaryUser = true;
	/** @var bool */
	protected $override = true;
	protected string $trustedSkinFlag;
	protected string $profileHash;

	/**
	 * @param SkinAnimation[] $animationFrames
	 */
	public function __construct(
		string $skinId,
		string $playFabId,
		SkinImage $skinImage,
		string $capeId,
		SkinImage $capeImage,
		string $resourcePatch,
		string $geometryData,
		string $geometryDataEngineVersion,
		string $animationData,
		array $animationFrames,
		bool $premiumSkin,
		bool $personaSkin,
		bool $capeOnClassicSkin,
		?string $fullSkinId,
		string $armSize,
		Color $skinColor,
		SplFixedArray $personaPieces,
		SplFixedArray $pieceTintColors,
		bool $isPrimaryUser = true,
		bool $override = true,
		string $trustedSkinFlag = "true",
		string $profileHash = ""
	) {
		if ($geometryData !== "") {
			$decodedData = (new Comment())->decode($geometryData);
			if ($decodedData === false) {
				throw new InvalidArgumentException("Invalid geometry data (" . json_last_error_msg() . ")");
			}
			$geometryData = json_encode($decodedData);
		}

		$decodedPatch = json_decode($resourcePatch);
		if (!isset($decodedPatch->geometry->default)) {
			throw new InvalidArgumentException("Invalid resource patch: $resourcePatch");
		}

		$resourcePatch = json_encode($decodedPatch);
		$this->skinId = $skinId;
		$this->playFabId = $playFabId;
		$this->skinImage = $skinImage;
		$this->capeId = $capeId;
		$this->capeImage = $capeImage;
		$this->resourcePatch = $resourcePatch;
		$this->geometryData = $geometryData;
		$this->geometryDataEngineVersion = $geometryDataEngineVersion;
		$this->animationData = $animationData;
		$this->animationFrames = $animationFrames;
		$this->premiumSkin = $premiumSkin;
		$this->personaSkin = $personaSkin;
		$this->capeOnClassicSkin = $capeOnClassicSkin;
		$this->fullSkinId = $fullSkinId === null || $fullSkinId === "" ? $this->generateFullSkinId() : $fullSkinId;
		$this->armSize = $armSize;
		$this->skinColor = $skinColor;
		$this->personaPieces = $personaPieces;
		$this->pieceTintColors = $pieceTintColors;
		$this->isPrimaryUser = $isPrimaryUser;
		$this->override = $override;
		$this->trustedSkinFlag = $trustedSkinFlag;
		$this->profileHash = $profileHash;
	}

	public function getSkinId() : string
	{
		return $this->skinId;
	}

	public function getPlayFabId() : string
	{
		return $this->playFabId;
	}

	public function getSkinImage() : SkinImage
	{
		return $this->skinImage;
	}

	public function withSkinImage(SkinImage $image) : self
	{
		$skin = clone $this;
		$skin->skinImage = $image;
		$skin->fullSkinId = $skin->generateFullSkinId();
		return $skin;
	}

	public function getCapeId() : string
	{
		return $this->capeId;
	}

	public function getCapeImage() : SkinImage
	{
		return $this->capeImage;
	}

	public function getResourcePatch() : string
	{
		return $this->resourcePatch;
	}

	public function getGeometryData() : string
	{
		return $this->geometryData;
	}

	public function getGeometryDataEngineVersion() : string
	{
		return $this->geometryDataEngineVersion;
	}

	public function getAnimationData() : string
	{
		return $this->animationData;
	}

	/**
	 * @return SkinAnimation[]
	 */
	public function getAnimationFrames() : array
	{
		return $this->animationFrames;
	}

	public function isPremiumSkin() : bool
	{
		return $this->premiumSkin;
	}

	public function isPersonaSkin() : bool
	{
		return $this->personaSkin;
	}

	public function isCapeOnClassicSkin() : bool
	{
		return $this->capeOnClassicSkin;
	}

	public function isPrimaryUser() : bool
	{
		return $this->isPrimaryUser;
	}

	public function isOverride() : bool
	{
		return $this->override;
	}

	public function getFullSkinId() : string
	{
		return $this->fullSkinId;
	}

	public function getArmSize() : string
	{
		return $this->armSize;
	}

	public function getSkinColor() : Color
	{
		return $this->skinColor;
	}

	public function getPersonaPieces() : SplFixedArray
	{
		return $this->personaPieces;
	}

	public function getPieceTintColors() : SplFixedArray
	{
		return $this->pieceTintColors;
	}

	public function isTrustedSkin() : bool
	{
		return strtolower($this->trustedSkinFlag) === "true";
	}

	public function getTrustedSkinFlag() : string
	{
		return $this->trustedSkinFlag;
	}

	public function getProfileHash() : string
	{
		return $this->profileHash;
	}

	public function setIsTrustedSkin(bool $isTrusted) : void
	{
		$this->isTrusted = $isTrusted;
	}

	public function toSkin() : Skin
	{
		if (!(
			($this->skinImage->getWidth() === 64 && ($this->skinImage->getHeight() === 32 || $this->skinImage->getHeight() === 64))
			||
			($this->skinImage->getWidth() === 128 && $this->skinImage->getHeight() === 128)
			||
			($this->skinImage->getWidth() === 256 && ($this->skinImage->getHeight() === 128 || $this->skinImage->getHeight() === 256))
		)) {
			self::$defaultSkins = self::$defaultSkins ?? [
				"steve" => new Skin("Standard_Steve", file_get_contents(RESOURCE_PATH . '/vanilla/skins/steve.skindata')),
				"alex" => new Skin("Standard_Alex", file_get_contents(RESOURCE_PATH . '/vanilla/skins/alex.skindata')),
			];

			$skin = clone self::$defaultSkins[$this->armSize === self::ARM_SIZE_SLIM ? "alex" : "steve"];
			$skin->setSerializedSkin($this);

			return $skin;
		}

		$skinGeometryData = $this->geometryData === "" ? [] : (new Comment())->decode($this->geometryData, true);
		if (isset($skinGeometryData["format_version"])) {
			unset($skinGeometryData["format_version"]);
			if (isset($skinGeometryData["minecraft:geometry"])) {
				foreach ($skinGeometryData["minecraft:geometry"] as $geometry) {
					$skinGeometryData[$geometry["description"]["identifier"]] = [
						"texturewidth" => $geometry["description"]["texture_width"],
						"textureheight" => $geometry["description"]["texture_height"],
						"bones" => $geometry["bones"]
					];
				}

				unset($skinGeometryData["minecraft:geometry"]);
			}
			$skinGeometryData = json_encode($skinGeometryData);
		} else {
			unset($skinGeometryData);
		}

		$skin = new Skin(
			$this->skinId,
			$this->skinImage->getData(),
			$this->capeImage->getData(),
			json_decode($this->resourcePatch)->geometry->default ?? self::GEOMETRY_CUSTOM,
			$skinGeometryData ?? $this->geometryData
		);
		$skin->setSerializedSkin($this);

		return $skin;
	}

	/**
	 * Hack to fix skins conflict.
	 * Full skin ID must be unique for any set of data.
	 */
	public function generateFullSkinId() : string
	{
		return UUID::fromData(
			$this->skinId,
			$this->resourcePatch,
			$this->skinImage->getData(),
			$this->capeImage->getData(),
			$this->geometryData,
			(string) $this->premiumSkin,
			(string) $this->personaSkin,
			(string) $this->capeOnClassicSkin,
			$this->capeId
		)->toString();
	}

}
