<?php


declare(strict_types=1);

/**
 * All the entity classes
 */

namespace pocketmine\entity;

use ErrorException;
use InvalidArgumentException;
use InvalidStateException;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\block\Lava;
use pocketmine\block\NetherPortal;
use pocketmine\block\Water;
use pocketmine\entity\hostile\Blaze;
use pocketmine\entity\hostile\CaveSpider;
use pocketmine\entity\hostile\Creeper;
use pocketmine\entity\hostile\Enderman;
use pocketmine\entity\hostile\Husk;
use pocketmine\entity\hostile\MagmaCube;
use pocketmine\entity\hostile\Skeleton;
use pocketmine\entity\hostile\Slime;
use pocketmine\entity\hostile\Spider;
use pocketmine\entity\hostile\Stray;
use pocketmine\entity\hostile\Witch;
use pocketmine\entity\hostile\Zombie;
use pocketmine\entity\object\ArmorStand;
use pocketmine\entity\object\EnderCrystal;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\FallingBlock;
use pocketmine\entity\object\FireworksRocket;
use pocketmine\entity\object\ItemEntity;
use pocketmine\entity\object\LeashKnot;
use pocketmine\entity\object\Painting;
use pocketmine\entity\object\PaintingMotive;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\entity\passive\Cat;
use pocketmine\entity\passive\Chicken;
use pocketmine\entity\passive\Cow;
use pocketmine\entity\passive\Horse;
use pocketmine\entity\passive\Mooshroom;
use pocketmine\entity\passive\Ocelot;
use pocketmine\entity\passive\Pig;
use pocketmine\entity\passive\Sheep;
use pocketmine\entity\passive\Squid;
use pocketmine\entity\passive\Villager;
use pocketmine\entity\passive\Wolf;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\projectile\ExperienceBottle;
use pocketmine\entity\projectile\FishingHook;
use pocketmine\entity\projectile\SmallFireball;
use pocketmine\entity\projectile\Snowball;
use pocketmine\entity\projectile\SplashPotion;
use pocketmine\entity\vehicle\Boat;
use pocketmine\entity\vehicle\Minecart;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityDismountEvent;
use pocketmine\event\entity\EntityFallEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityMountEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\item\Item;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\Chunk;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\SimpleChunkManager;
use pocketmine\level\sound\PlaySound;
use pocketmine\level\sound\Sound;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\SetActorMotionPacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\timings\Timings;
use pocketmine\timings\TimingsHandler;
use pocketmine\utils\Random;
use pocketmine\utils\Utils;
use pocketmine\utils\UUID;
use ReflectionClass;

use function abs;
use function array_map;
use function array_search;
use function assert;
use function cos;
use function count;
use function deg2rad;
use function floor;
use function fmod;
use function get_class;
use function in_array;
use function intval;
use function is_a;
use function is_array;
use function is_infinite;
use function is_nan;
use function max;
use function microtime;
use function min;
use function pi;
use function reset;
use function sin;
use function spl_object_id;

use function sqrt;
use const M_PI_2;

abstract class Entity extends Location implements EntityIds, EntityMetadataProperties, EntityMetadataFlags
{
        public const MOTION_THRESHOLD = 0.00001;
        protected const STEP_CLIP_MULTIPLIER = 0.4;

        public const NETWORK_ID = -1;

        public const DATA_TYPE_BYTE = 0;
        public const DATA_TYPE_SHORT = 1;
        public const DATA_TYPE_INT = 2;
        public const DATA_TYPE_FLOAT = 3;
        public const DATA_TYPE_STRING = 4;
        public const DATA_TYPE_SLOT = 5;
        public const DATA_TYPE_POS = 6;
        public const DATA_TYPE_LONG = 7;
        public const DATA_TYPE_VECTOR3F = 8;

        public const DATA_PLAYER_FLAG_SLEEP = 1;
        public const DATA_PLAYER_FLAG_DEAD = 2; //TODO: CHECK

        public const SPAWN_PLACEMENT_TYPE = SpawnPlacementTypes::PLACEMENT_TYPE_ON_GROUND;

        /** @var int */
        public static $entityCount = 1;
        /**
         * @var string[]
         * @phpstan-var array<int|string, class-string<Entity>>
         */
        private static $knownEntities = [];
        /**
         * @var string[]
         * @phpstan-var array<class-string<Entity>, string>
         */
        private static $saveNames = [];

        /**
         * Called on server startup to register default entity types.
         */
        public static function init() : void
        {
                //define legacy save IDs first - use them for saving for maximum compatibility with Minecraft PC
                //TODO: index them by version to allow proper multi-save compatibility

                Entity::registerEntity(Arrow::class, false, ['Arrow', 'minecraft:arrow']);
                Entity::registerEntity(Egg::class, false, ['Egg', 'minecraft:egg']);
                Entity::registerEntity(EnderCrystal::class, false, ['EnderCrystal', 'minecraft:ender_crystal']);
                Entity::registerEntity(EnderPearl::class, false, ['ThrownEnderpearl', 'minecraft:ender_pearl']);
                Entity::registerEntity(ExperienceBottle::class, false, ['ThrownExpBottle', 'minecraft:xp_bottle']);
                Entity::registerEntity(ExperienceOrb::class, false, ['XPOrb', 'minecraft:xp_orb']);
                Entity::registerEntity(FallingBlock::class, false, ['FallingSand', 'minecraft:falling_block']);
                Entity::registerEntity(ItemEntity::class, false, ['Item', 'minecraft:item']);
                Entity::registerEntity(Painting::class, false, ['Painting', 'minecraft:painting']);
                Entity::registerEntity(PrimedTNT::class, false, ['PrimedTnt', 'PrimedTNT', 'minecraft:tnt']);
                Entity::registerEntity(Snowball::class, false, ['Snowball', 'minecraft:snowball']);
                Entity::registerEntity(SplashPotion::class, false, ['ThrownPotion', 'minecraft:potion', 'thrownpotion']);
                Entity::registerEntity(Slime::class, false, ['Slime', 'minecraft:slime']);
                Entity::registerEntity(MagmaCube::class, false, ['MagmaCube', 'minecraft:magma_cube']);
                Entity::registerEntity(Boat::class, false, ['Boat', 'minecraft:boat']);
                Entity::registerEntity(Minecart::class, false, ['Minecart', 'minecraft:minecart']);
                Entity::registerEntity(Horse::class, false, ['Horse', 'minecraft:horse']);
                Entity::registerEntity(FireworksRocket::class, false, ['FireworksRocket', 'minecraft:fireworks_rocket']);
                Entity::registerEntity(Blaze::class, false, ['Blaze', 'minecraft:blaze']);
                Entity::registerEntity(SmallFireball::class, false, ['SmallFireball', 'minecraft:small_fireball']);
                Entity::registerEntity(Squid::class, false, ['Squid', 'minecraft:squid']);
                Entity::registerEntity(Villager::class, false, ['Villager', 'minecraft:villager']);
                Entity::registerEntity(Wolf::class, false, ['Wolf', 'minecraft:wolf']);
                Entity::registerEntity(Zombie::class, false, ['Zombie', 'minecraft:zombie']);
                Entity::registerEntity(Cow::class, false, ['Cow', 'minecraft:cow']);
                Entity::registerEntity(Sheep::class, false, ['Sheep', 'minecraft:sheep']);
                Entity::registerEntity(Mooshroom::class, false, ['Mooshroom', 'minecraft:mooshroom']);
                Entity::registerEntity(Ocelot::class, false, ['Ocelot', 'minecraft:ocelot']);
                Entity::registerEntity(Pig::class, false, ['Pig', 'minecraft:pig']);
                Entity::registerEntity(Cat::class, false, ['Cat', 'minecraft:cat']);
                Entity::registerEntity(Skeleton::class, false, ['Skeleton', 'minecraft:skeleton']);
                Entity::registerEntity(Stray::class, false, ['Stray', 'minecraft:stray']);
                Entity::registerEntity(Witch::class, false, ['Witch', 'minecraft:witch']);
                Entity::registerEntity(Husk::class, false, ['Husk', 'minecraft:husk']);
                Entity::registerEntity(Chicken::class, false, ['Chicken', 'minecraft:chicken']);
                Entity::registerEntity(Spider::class, false, ['Spider', 'minecraft:spider']);
                Entity::registerEntity(CaveSpider::class, false, ['CaveSpider', 'minecraft:cave_spider']);
                Entity::registerEntity(Creeper::class, false, ['Creeper', 'minecraft:creeper']);
                Entity::registerEntity(Enderman::class, false, ['Enderman', 'minecraft:enderman']);
                Entity::registerEntity(FishingHook::class, false, ['FishingHook', 'minecraft:fishing_hook']);
                Entity::registerEntity(LeashKnot::class, false, ['LeashKnot', 'minecraft:leash_knot']);
                Entity::registerEntity(ArmorStand::class, false, ['ArmorStand", "minecraft:armor_stand']);

                Entity::registerEntity(Human::class, true);

                Attribute::init();
                Effect::init();
                PaintingMotive::init();
        }

        /**
         * Creates an entity with the specified type, level and NBT, with optional additional arguments to pass to the
         * entity's constructor
         *
         * @param int|string $type
         * @param mixed      ...$args
         */
        public static function createEntity($type, ChunkManager $level, CompoundTag $nbt, ...$args) : ?Entity
        {
                if (isset(self::$knownEntities[$type])) {
                        $class = self::$knownEntities[$type];
                        /** @see Entity::__construct() */
                        return new $class($level, $nbt, ...$args);
                }

                return null;
        }

        /**
         * Registers an entity type into the index.
         *
         * @param string   $className Class that extends Entity
         * @param bool     $force     Force registration even if the entity does not have a valid network ID
         * @param string[] $saveNames An array of save names which this entity might be saved under. Defaults to the short name of the class itself if empty.
         *
         * @phpstan-param class-string<Entity> $className
         *
         * NOTE: The first save name in the $saveNames array will be used when saving the entity to disk. The reflection
         * name of the class will be appended to the end and only used if no other save names are specified.
         * @throws \ReflectionException
         */
        public static function registerEntity(string $className, bool $force = false, array $saveNames = []) : bool
        {
                $class = new ReflectionClass($className);
                if (is_a($className, Entity::class, true) && !$class->isAbstract()) {
                        if ($className::NETWORK_ID !== -1) {
                                self::$knownEntities[$className::NETWORK_ID] = $className;
                        } elseif (!$force) {
                                return false;
                        }

                        $shortName = $class->getShortName();
                        if (!in_array($shortName, $saveNames, true)) {
                                $saveNames[] = $shortName;
                        }

                        foreach ($saveNames as $name) {
                                self::$knownEntities[$name] = $className;
                        }

                        self::$saveNames[$className] = reset($saveNames);

                        return true;
                }

                return false;
        }

        /**
         * Helper function which creates minimal NBT needed to spawn an entity.
         */
        public static function createBaseNBT(Vector3 $pos, ?Vector3 $motion = null, float $yaw = 0.0, float $pitch = 0.0) : CompoundTag
        {
                return new CompoundTag("", [
                        new ListTag("Pos", [
                                new DoubleTag("", $pos->x),
                                new DoubleTag("", $pos->y),
                                new DoubleTag("", $pos->z)
                        ]),
                        new ListTag("Motion", [
                                new DoubleTag("", $motion !== null ? $motion->x : 0.0),
                                new DoubleTag("", $motion !== null ? $motion->y : 0.0),
                                new DoubleTag("", $motion !== null ? $motion->z : 0.0)
                        ]),
                        new ListTag("Rotation", [
                                new FloatTag("", $yaw),
                                new FloatTag("", $pitch)
                        ])
                ]);
        }

        /** @var Player[] */
        protected array $hasSpawned = [];

        protected int $id;

        protected DataPropertyManager $propertyManager;

        public ?Chunk $chunk = null;

        protected ?EntityDamageEvent $lastDamageCause = null;

        /** @var Block[]|null */
        protected ?array $blocksAround = null;

        public float $lastX;
        public float $lastY;
        public float $lastZ;

        protected Vector3 $motion;
        protected Vector3 $lastMotion;
        protected bool $forceMovementUpdate = false;

        public float $lastYaw;
        public float $lastPitch;

        public AxisAlignedBB $boundingBox;
        public bool $onGround;

        public ?float $eyeHeight = null;

        public float $height = 0.0;
        public float $width = 0.0;

        protected float $baseOffset = 0.0;

        private float $health = 20.0;
        private int $maxHealth = 20;

        protected float $ySize = 0.0;
        protected float $stepHeight = 0.0;
        public bool $keepMovement = false;

        public float $fallDistance = 0.0;
        public int $ticksLived = 0;
        public int $lastUpdate = 0;
        protected int $fireTicks = 0;
        public ?CompoundTag $namedtag = null;
        public bool $canCollide = true;
        private bool $savedWithChunk = true;

        public bool $isCollided = false;
        public bool $isCollidedHorizontally = false;
        public bool $isCollidedVertically = false;

        public int $noDamageTicks = 0;
        protected bool $justCreated = true;
        private bool $invulnerable;

        protected AttributeMap $attributeMap;

        /** @var float */
        protected $gravity;
        /** @var float */
        protected $drag;

        protected Server $server;

        protected bool $closed = false;
        private bool $needsDespawn = false;

        protected TimingsHandler $timings;

        protected bool $constructed = false;

        protected ?int $ridingEid = null;
        protected ?int $riddenByEid = null;
        protected float $entityRiderPitchDelta = 0;
        protected float $entityRiderYawDelta = 0;
        /** @var int[] */
        public array $passengers = [];
        public Random $random;
        protected ?UUID $uuid = null;
        protected bool $inPortal = false;
        protected int $timeUntilPortal = 0;
        protected int $portalCounter = 0;

        /**
         * Per-level cache of known Nether portal positions. Keyed by the level's
         * folder name so that the cache survives across dimension switches and
         * short-circuits the expensive block-by-block scan in findNetherPortal().
         * Each entry is a list of Vector3 (bottom-interior portal block).
         *
         * @var array<string, Vector3[]>
         */
        protected static array $portalCache = [];

        public ?float $headYaw = null;
        public float $lastHeadYaw = 0;
        private bool $closeInFlight = false;

        protected int $clientMoveTicks = 0;

        protected Vector3 $clientPos;
        protected float $clientYaw = 0;
        protected float $clientPitch = 0;

        protected bool $isKilled = false;

        public function __construct(ChunkManager $level, CompoundTag $nbt)
        {
                $this->random = new Random(intval(microtime(true) * 1000));
                $this->constructed = true;
                if ($level instanceof Level) {
                        $this->timings = Timings::getEntityTimings($this);
                }

                if ($this->eyeHeight === null) {
                        $this->eyeHeight = $this->height * 0.85;
                }

                $this->id = Entity::$entityCount++;
                $this->namedtag = $nbt;
                if ($level instanceof Level) {
                        $this->server = $level->getServer();
                }

                /** @var float[] $pos */
                $pos = $this->namedtag->getListTag("Pos")->getAllValues();
                /** @var float[] $rotation */
                $rotation = $this->namedtag->getListTag("Rotation")->getAllValues();

                parent::__construct($pos[0], $pos[1], $pos[2], $rotation[0], $rotation[1], $level instanceof Level ? $level : null);
                assert(!is_nan($this->x) && !is_infinite($this->x) && !is_nan($this->y) && !is_infinite($this->y) && !is_nan($this->z) && !is_infinite($this->z));

                $this->boundingBox = new AxisAlignedBB(0, 0, 0, 0, 0, 0);
                $this->recalculateBoundingBox();

                if ($level instanceof Level) {
                        $this->chunk = $this->level->getChunkAtPosition($this, false);
                        if ($this->chunk === null) {
                                throw new InvalidStateException("Cannot create entities in unloaded chunks");
                        }
                }

                $this->motion = new Vector3(0, 0, 0);
                if ($this->namedtag->hasTag("Motion", ListTag::class)) {
                        /** @var float[] $motion */
                        $motion = $this->namedtag->getListTag("Motion")->getAllValues();
                        $this->setMotion(new Vector3(...$motion));
                }

                $this->resetLastMovements();

                $this->fallDistance = $this->namedtag->getFloat("FallDistance", 0.0);

                $this->propertyManager = new DataPropertyManager($this);

                $this->propertyManager->setLong(self::DATA_FLAGS, 0);
                $this->propertyManager->setByte(self::DATA_COLOR, 0);
                $this->propertyManager->setShort(self::DATA_MAX_AIR, 400);
                $this->propertyManager->setString(self::DATA_NAMETAG, "");
                $this->propertyManager->setLong(self::DATA_LEAD_HOLDER_EID, -1);
                $this->propertyManager->setFloat(self::DATA_SCALE, 1);
                $this->propertyManager->setFloat(self::DATA_BOUNDING_BOX_WIDTH, $this->width);
                $this->propertyManager->setFloat(self::DATA_BOUNDING_BOX_HEIGHT, $this->height);

                $this->fireTicks = $this->namedtag->getTagValue("Fire", NamedTag::class, 0);
                if ($this->isOnFire()) {
                        $this->setGenericFlag(self::DATA_FLAG_ONFIRE);
                }

                $this->propertyManager->setShort(self::DATA_AIR, $this->namedtag->getShort("Air", 300));
                $this->onGround = $this->namedtag->getByte("OnGround", 0) !== 0;
                $this->invulnerable = $this->namedtag->getByte("Invulnerable", 0) !== 0;
                $this->setScale($this->namedtag->getFloat("Scale", 1));

                $this->attributeMap = new AttributeMap();
                $this->addAttributes();

                $this->setGenericFlag(self::DATA_FLAG_AFFECTED_BY_GRAVITY, true);
                $this->setGenericFlag(self::DATA_FLAG_HAS_COLLISION, true);

                $this->initEntity();
                $this->propertyManager->clearDirtyProperties(); //Prevents resending properties that were set during construction

                $level->addEntity($this);
                if ($level instanceof Level) {
                        $this->chunk->addEntity($this);

                        $this->lastUpdate = $this->server->getTick();
                        (new EntitySpawnEvent($this))->call();

                        $this->scheduleUpdate();
                }
        }

        public function getName() : string{
                return (new ReflectionClass($this))->getShortName();
        }

        public function getNameTag() : string
        {
                return $this->propertyManager->getString(self::DATA_NAMETAG);
        }

        public function isNameTagVisible() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_CAN_SHOW_NAMETAG);
        }

        public function isNameTagAlwaysVisible() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_ALWAYS_SHOW_NAMETAG);
        }

        public function setNameTag(string $name) : void
        {
                $this->propertyManager->setString(self::DATA_NAMETAG, $name);
        }

        public function setNameTagVisible(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_CAN_SHOW_NAMETAG, $value);
        }

        public function setNameTagAlwaysVisible(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_ALWAYS_SHOW_NAMETAG, $value);
        }

        public function getScoreTag() : ?string
        {
                return $this->propertyManager->getString(self::DATA_SCORE_TAG);
        }

        public function setScoreTag(string $score) : void
        {
                $this->propertyManager->setString(self::DATA_SCORE_TAG, $score);
        }

        public function getScale() : float
        {
                return $this->propertyManager->getFloat(self::DATA_SCALE);
        }

        public function setScale(float $value) : void
        {
                if ($value <= 0) {
                        throw new InvalidArgumentException("Scale must be greater than 0");
                }
                $multiplier = $value / $this->getScale();

                $this->width *= $multiplier;
                $this->height *= $multiplier;
                $this->eyeHeight *= $multiplier;

                $this->recalculateBoundingBox();

                $this->propertyManager->setFloat(self::DATA_SCALE, $value);
        }

        public function isInLove() : bool
        {
                return $this->getDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INLOVE);
        }

        public function setInLove(bool $value) : void
        {
                $this->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INLOVE, $value);
        }

        public function isRiding() : bool
        {
                return $this->getDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_RIDING);
        }

        public function setRiding(bool $value) : void
        {
                $this->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_RIDING, $value);
        }

        public function getRidingEntity() : ?Entity
        {
                return $this->ridingEid !== null ? $this->server->findEntity($this->ridingEid) : null;
        }

        public function setRidingEntity(?Entity $ridingEntity = null) : void
        {
                if ($ridingEntity instanceof Entity) {
                        $this->ridingEid = $ridingEntity->getId();
                } else {
                        $this->ridingEid = null;
                }
        }

        public function getRiddenByEntity() : ?Entity
        {
                return $this->riddenByEid !== null ? $this->server->findEntity($this->riddenByEid) : null;
        }

        public function setRiddenByEntity(?Entity $riddenByEntity = null) : void
        {
                if ($riddenByEntity instanceof Entity) {
                        $this->riddenByEid = $riddenByEntity->getId();
                } else {
                        $this->riddenByEid = null;
                }
        }

        public function isBaby() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_BABY);
        }

        public function setBaby(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_BABY, $value);
                $this->setScale($value ? 0.5 : 1.0);
        }

        public function isInPortal() : bool
        {
                return $this->inPortal;
        }

        public function setInPortal(bool $inPortal) : void
        {
                // Don't activate the portal teleport while on cooldown.
                // This prevents "bounce-back": player arrives at the destination
                // portal, and because they're standing in it, would immediately
                // start the warm-up to teleport back. With the cooldown gate,
                // the player has time to walk out of the portal before being
                // sent back through it.
                if ($inPortal && $this->timeUntilPortal > 0) {
                        return;
                }
                $this->inPortal = $inPortal;
        }

        public function getBoundingBox() : AxisAlignedBB
        {
                return $this->boundingBox;
        }

        protected function recalculateBoundingBox() : void
        {
                $halfWidth = $this->width / 2;

                $this->boundingBox = new AxisAlignedBB(
                        $this->x - $halfWidth,
                        $this->y + $this->ySize,
                        $this->z - $halfWidth,
                        $this->x + $halfWidth,
                        $this->y + $this->height + $this->ySize,
                        $this->z + $halfWidth
                );
        }

        /**
         * Update entity's height and width
         */
        public function updateBoundingBox(float $height, float $width) : void
        {
                $this->height = $height;
                $this->width = $width;

                $this->recalculateBoundingBox();
                $this->propertyManager->setFloat(self::DATA_BOUNDING_BOX_WIDTH, $width);
                $this->propertyManager->setFloat(self::DATA_BOUNDING_BOX_HEIGHT, $height);
        }

        public function isAffectedByGravity() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_AFFECTED_BY_GRAVITY);
        }

        public function setAffectedByGravity(bool $value = true)
        {
                $this->setGenericFlag(self::DATA_FLAG_AFFECTED_BY_GRAVITY, $value);
        }

        public function isSneaking() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_SNEAKING);
        }

        public function setSneaking(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_SNEAKING, $value);
        }

        public function isSprinting() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_SPRINTING);
        }

        public function setSprinting(bool $value = true) : void
        {
                if ($value !== $this->isSprinting()) {
                        $this->setGenericFlag(self::DATA_FLAG_SPRINTING, $value);
                        $attr = $this->attributeMap->getAttribute(Attribute::MOVEMENT_SPEED);
                        $attr->setValue($value ? ($attr->getValue() * 1.3) : ($attr->getValue() / 1.3), false, true);
                }
        }

        public function isSwimming() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_SWIMMING);
        }

        public function setSwimming(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_SWIMMING, $value);
        }

        public function isSwimmer() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_SWIMMER);
        }

        public function setSwimmer(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_SWIMMER, $value);
        }

        public function isCrawling() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_CRAWLING);
        }

        public function setCrawling(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_CRAWLING, $value);
        }

        public function isImmobile() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_IMMOBILE);
        }

        public function setImmobile(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_IMMOBILE, $value);
        }

        public function isInvisible() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_INVISIBLE);
        }

        public function setInvisible(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_INVISIBLE, $value);
        }

        public function isSilent() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_SILENT);
        }

        public function setSilent(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_SILENT, $value);
        }

        public function isGliding() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_GLIDING);
        }

        public function setGliding(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_GLIDING, $value);
        }

        /**
         * Returns whether the entity is able to climb blocks such as ladders or vines.
         */
        public function canClimb() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_CAN_CLIMB);
        }

        /**
         * Sets whether the entity is able to climb climbable blocks.
         */
        public function setCanClimb(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_CAN_CLIMB, $value);
        }

        /**
         * Returns whether the entity is able to fly
         */
        public function canFly() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_CAN_FLY);
        }

        /**
         * Sets whether the entity is able to fly
         */
        public function setCanFly(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_CAN_FLY, $value);
        }

        /**
         * Returns whether this entity is climbing a block. By default this is only true if the entity is climbing a ladder or vine or similar block.
         */
        public function canClimbWalls() : bool
        {
                return $this->getGenericFlag(self::DATA_FLAG_WALLCLIMBING);
        }

        /**
         * Sets whether the entity is climbing a block. If true, the entity can climb anything.
         */
        public function setCanClimbWalls(bool $value = true) : void
        {
                $this->setGenericFlag(self::DATA_FLAG_WALLCLIMBING, $value);
        }

        /**
         * Returns the entity ID of the owning entity, or null if the entity doesn't have an owner.
         */
        public function getOwningEntityId() : ?int{
                return $this->propertyManager->getLong(self::DATA_OWNER_EID);
        }

        /**
         * Returns the owning entity, or null if the entity was not found.
         */
        public function getOwningEntity() : ?Entity
        {
                $eid = $this->getOwningEntityId();
                if ($eid !== null) {
                        return $this->server->findEntity($eid);
                }

                return null;
        }

        /**
         * Sets the owner of the entity. Passing null will remove the current owner.
         *
         * @throws InvalidArgumentException if the supplied entity is not valid
         */
        public function setOwningEntity(?Entity $owner) : void
        {
                if ($owner === null) {
                        $this->propertyManager->removeProperty(self::DATA_OWNER_EID);
                } elseif ($owner->closed) {
                        throw new InvalidArgumentException("Supplied owning entity is garbage and cannot be used");
                } else {
                        $this->propertyManager->setLong(self::DATA_OWNER_EID, $owner->getId());
                }
        }

        /**
         * Returns the entity ID of the entity's target, or null if it doesn't have a target.
         */
        public function getTargetEntityId() : ?int
        {
                return $this->propertyManager->getLong(self::DATA_TARGET_EID);
        }

        /**
         * Returns the entity's target entity, or null if not found.
         * This is used for things like hostile mobs attacking entities, and for fishing rods reeling hit entities in.
         */
        public function getTargetEntity() : ?Entity
        {
                $eid = $this->getTargetEntityId();
                if ($eid !== null) {
                        return $this->server->findEntity($eid);
                }

                return null;
        }

        /**
         * Sets the entity's target entity. Passing null will remove the current target.
         *
         * @throws InvalidArgumentException if the target entity is not valid
         */
        public function setTargetEntity(?Entity $target) : void
        {
                if ($target === null) {
                        $this->propertyManager->removeProperty(self::DATA_TARGET_EID);
                } elseif ($target->closed) {
                        throw new InvalidArgumentException("Supplied target entity is garbage and cannot be used");
                } else {
                        $this->propertyManager->setLong(self::DATA_TARGET_EID, $target->getId());
                }
        }

        /**
         * Returns whether this entity will be saved when its chunk is unloaded.
         */
        public function canSaveWithChunk() : bool
        {
                return $this->savedWithChunk;
        }

        /**
         * Sets whether this entity will be saved when its chunk is unloaded. This can be used to prevent the entity being
         * saved to disk.
         */
        public function setCanSaveWithChunk(bool $value) : void
        {
                $this->savedWithChunk = $value;
        }

        /**
         * Returns the short save name
         */
        public function getSaveId() : string
        {
                if (!isset(self::$saveNames[static::class])) {
                        throw new InvalidStateException("Entity " . static::class . " is not registered");
                }
                return self::$saveNames[static::class];
        }

        public function saveNBT() : void
        {
                if (!($this instanceof Player)) {
                        $this->namedtag->setString("id", $this->getSaveId(), true);

                        if ($this->getNameTag() !== "") {
                                $this->namedtag->setString("CustomName", $this->getNameTag());
                                $this->namedtag->setByte("CustomNameVisible", $this->isNameTagVisible() ? 1 : 0);
                                $this->namedtag->setByte("CustomNameAlwaysVisible", $this->isNameTagAlwaysVisible() ? 1 : 0);
                        } else {
                                $this->namedtag->removeTag("CustomName", "CustomNameVisible", "CustomNameAlwaysVisible");
                        }

                        if ($this->uuid !== null) {
                                $this->namedtag->setString("UUID", $this->uuid->toString());
                        }
                }

                $this->namedtag->setTag(new ListTag("Pos", [
                        new DoubleTag("", $this->x),
                        new DoubleTag("", $this->y),
                        new DoubleTag("", $this->z)
                ]));

                $this->namedtag->setTag(new ListTag("Motion", [
                        new DoubleTag("", $this->motion->x),
                        new DoubleTag("", $this->motion->y),
                        new DoubleTag("", $this->motion->z)
                ]));

                $this->namedtag->setTag(new ListTag("Rotation", [
                        new FloatTag("", $this->yaw),
                        new FloatTag("", $this->pitch)
                ]));

                $this->namedtag->setFloat("FallDistance", $this->fallDistance);
                $this->namedtag->setInt("Fire", $this->fireTicks, true);
                $this->namedtag->setShort("Air", $this->propertyManager->getShort(self::DATA_AIR));
                $this->namedtag->setByte("OnGround", $this->onGround ? 1 : 0);
                $this->namedtag->setByte("Invulnerable", $this->invulnerable ? 1 : 0);
                $this->namedtag->setFloat("Scale", $this->getScale());

                // TODO: Save passengers
        }

        protected function initEntity() : void
        {
                assert($this->namedtag instanceof CompoundTag);

                if ($this->namedtag->hasTag("CustomName", StringTag::class)) {
                        $this->setNameTag($this->namedtag->getString("CustomName"));

                        if ($this->namedtag->hasTag("CustomNameVisible", StringTag::class)) {
                                //Older versions incorrectly saved this as a string (see 890f72dbf23a77f294169b79590770470041adc4)
                                $this->setNameTagVisible($this->namedtag->getString("CustomNameVisible") !== "");
                                $this->namedtag->removeTag("CustomNameVisible");
                        } else {
                                $this->setNameTagVisible($this->namedtag->getByte("CustomNameVisible", 1) !== 0);
                        }

                        if ($this->namedtag->hasTag("CustomNameAlwaysVisible", StringTag::class)) {
                                //Older versions incorrectly saved this as a string (see 890f72dbf23a77f294169b79590770470041adc4)
                                $this->setNameTagAlwaysVisible($this->namedtag->getString("CustomNameAlwaysVisible") !== "");
                                $this->namedtag->removeTag("CustomNameAlwaysVisible");
                        } else {
                                $this->setNameTagAlwaysVisible($this->namedtag->getByte("CustomNameAlwaysVisible", 1) !== 0);
                        }
                }

                if ($this->uuid === null) {
                        if ($this->namedtag->hasTag("UUID", StringTag::class)) {
                                $this->uuid = UUID::fromString($this->namedtag->getString("UUID"));
                        } else {
                                $this->uuid = UUID::fromRandom();
                        }
                }
        }

        public function getUniqueId() : ?UUID
        {
                return $this->uuid;
        }

        protected function addAttributes() : void
        {

        }

        public function entityShoot(Entity $shooter, float $pitchOffset, float $velocity, float $inaccuracy) : void
        {

        }

        public function shoot(Vector3 $direction, float $velocity, float $inaccuracy) : void
        {

        }

        public function attack(EntityDamageEvent $source) : void
        {
                if($this->isFireProof() && (
                                $source->getCause() === EntityDamageEvent::CAUSE_FIRE ||
                                $source->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK ||
                                $source->getCause() === EntityDamageEvent::CAUSE_LAVA
                        )
                ){
                        $source->setCancelled();
                }

                $source->call();
                if ($source->isCancelled()) {
                        return;
                }

                $this->setLastDamageCause($source);

                $this->setHealth($this->getHealth() - $source->getFinalDamage());
        }

        public function heal(EntityRegainHealthEvent $source) : void
        {
                $source->call();
                if ($source->isCancelled()) {
                        return;
                }

                $this->setHealth($this->getHealth() + $source->getAmount());
        }

        public function kill() : void
        {
                $this->isKilled = true;
                $this->health = 0;
                $this->dismountEntity(true);
                $this->scheduleUpdate();
        }

        /**
         * Called to tick entities while dead. Returns whether the entity should be flagged for despawn yet.
         */
        protected function onDeathUpdate(int $tickDiff) : bool
        {
                return true;
        }

        public function isAlive() : bool
        {
                return $this->health > 0;
        }

        public function getHealth() : float
        {
                return $this->health;
        }

        /**
         * Sets the health of the Entity. This won't send any update to the players
         */
        public function setHealth(float $amount) : void
        {
                if ($amount == $this->health) {
                        return;
                }

                if ($amount > 0) {
                        $this->isKilled = false;
                }

                if ($amount <= 0) {
                        $this->health = 0;
                        $this->scheduleUpdate();
                } elseif ($amount <= $this->getMaxHealth() || $amount < $this->health) {
                        $this->health = $amount;
                } else {
                        $this->health = $this->getMaxHealth();
                }
        }

        public function getMaxHealth() : int
        {
                return $this->maxHealth;
        }

        public function setMaxHealth(int $amount) : void
        {
                $this->maxHealth = $amount;
        }

        public function setLastDamageCause(?EntityDamageEvent $type) : void
        {
                $this->lastDamageCause = $type;
        }

        public function getLastDamageCause() : ?EntityDamageEvent
        {
                return $this->lastDamageCause;
        }

        public function getAttributeMap() : AttributeMap
        {
                return $this->attributeMap;
        }

        public function getDataPropertyManager() : DataPropertyManager
        {
                return $this->propertyManager;
        }

        public function entityBaseTick(int $tickDiff = 1) : bool
        {
                if ($this->getRidingEntity() === null && $this->ridingEid !== null) {
                        $this->ridingEid = null;
                        $this->setRiding(false);
                }

                if ($this->getRiddenByEntity() === null && $this->riddenByEid !== null) {
                        $this->riddenByEid = null;

                        unset($this->passengers[array_search($this->riddenByEid, $this->passengers, true)]);
                        $this->setGenericFlag(Entity::DATA_FLAG_WASD_CONTROLLED, false);
                }

                $this->justCreated = false;

                $changedProperties = $this->propertyManager->getDirty();
                if (!empty($changedProperties)) {
                        $this->sendData($this->hasSpawned, $changedProperties);
                        $this->propertyManager->clearDirtyProperties();
                }

                $hasUpdate = false;

                $this->checkBlockCollision();

                if ($this->y <= -16 && $this->isAlive()) {
                        $ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_VOID, 10);
                        $this->attack($ev);
                        $hasUpdate = true;
                }

                if ($this->isOnFire() && $this->doOnFireTick($tickDiff)) {
                        $hasUpdate = true;
                }

                if ($this->noDamageTicks > 0) {
                        $this->noDamageTicks -= $tickDiff;
                        if ($this->noDamageTicks < 0) {
                                $this->noDamageTicks = 0;
                        }
                }

                if ($this->isGliding()) {
                        $this->resetFallDistance();
                }

                if ($this->inPortal) {
                        if ($this->server->isAllowNether()) {
                                // Respect the post-teleport cooldown: don't increment the counter
                                // (and therefore don't trigger another teleport) while the cooldown
                                // is still active. This prevents a player from immediately bouncing
                                // back through the destination portal.
                                if ($this->timeUntilPortal <= 0 && !$this->isRiding() && $this->portalCounter++ > $this->getMaxInPortalTime()) {
                                        // Reset the counter so that the player has to stand in the
                                        // destination portal for the full warm-up time again before
                                        // being sent back, instead of teleporting on the very next
                                        // tick after the cooldown expires.
                                        $this->portalCounter = 0;
                                        $this->timeUntilPortal = $this->getPortalCooldown();

                                        $this->travelToDimension($this->level->getDimension() === DimensionIds::NETHER ? DimensionIds::OVERWORLD : DimensionIds::NETHER);

                                        $this->inPortal = false;
                                }
                        }
                } else {
                        if ($this->portalCounter > 0) {
                                $this->portalCounter -= 4;
                        }

                        if ($this->portalCounter < 0) {
                                $this->portalCounter = 0;
                        }
                }

                if ($this->timeUntilPortal > 0) {
                        $this->timeUntilPortal--;
                }

                $this->ticksLived += $tickDiff;
                return $hasUpdate;
        }

        public function getMaxInPortalTime() : int
        {
                return 0;
        }

        public function getPortalCooldown() : int
        {
                return 300;
        }

        public function travelToDimension(int $dimensionId) : void{
                if ($dimensionId === DimensionIds::NETHER) {
                        $targetLevel = $this->server->getNetherLevel();
                } elseif ($dimensionId === DimensionIds::THE_END) {
                        $targetLevel = $this->server->getTheEndLevel();
                } else {
                        $targetLevel = $this->server->getDefaultLevel();
                }

                if ($targetLevel === null || $targetLevel->isClosed()) {
                        $targetLevel = $this->server->getDefaultLevel();
                }

                if ($targetLevel === null || $targetLevel->isClosed()) {
                        return;
                }

                if ($dimensionId === DimensionIds::THE_END) {
                        $spawnPos = new Position(Level::END_SPAWN_POINT_X, Level::END_SPAWN_POINT_Y, Level::END_SPAWN_POINT_Z, $targetLevel);
                        $this->teleport($spawnPos);
                        return;
                }

                // Vanilla Nether portal behaviour: 1 block in the Nether = 8 blocks in the Overworld.
                // Compute the corresponding destination coordinates and try to reuse an existing
                // portal nearby. If none is found, build a fresh 4x5 portal at the destination.
                $currentDim = $this->level->getDimension();
                if ($currentDim === DimensionIds::NETHER && $dimensionId === DimensionIds::OVERWORLD) {
                        $targetX = (int) floor($this->x * 8);
                        $targetZ = (int) floor($this->z * 8);
                } elseif ($currentDim === DimensionIds::OVERWORLD && $dimensionId === DimensionIds::NETHER) {
                        $targetX = (int) floor($this->x / 8);
                        $targetZ = (int) floor($this->z / 8);
                } else {
                        $targetX = (int) floor($this->x);
                        $targetZ = (int) floor($this->z);
                }
                $targetY = (int) floor($this->y);

                $portalPos = $this->findNetherPortal($targetLevel, $targetX, $targetY, $targetZ);
                if ($portalPos === null) {
                        // Wrap portal creation in try-catch: if synchronous chunk generation
                        // fails (e.g. the Overworld generator throws during populateChunk),
                        // we must NOT let the exception propagate — that would leave the
                        // player stuck in the portal with inPortal=true, causing an
                        // infinite exception loop on every subsequent tick, which
                        // eventually kicks the player with "Internal server error".
                        try {
                                $portalPos = $this->createNetherPortal($targetLevel, $targetX, $targetY, $targetZ);
                        } catch (\Throwable $e) {
                                $this->server->getLogger()->warning("Failed to create Nether portal at ($targetX, $targetY, $targetZ): " . $e->getMessage());
                                $this->server->getLogger()->logException($e);
                                $portalPos = null;
                        }
                }

                if ($portalPos !== null) {
                        // Center the player between the two portal blocks of the bottom-interior row.
                        // For X-axis portals (portal at x, x+1; z fixed), center = (x+1, y, z+0.5).
                        // For Z-axis portals (portal at z, z+1; x fixed), center = (x+0.5, y, z+1).
                        // Fallback (single block): just stand in the middle of the returned block.
                        $px = $portalPos->x;
                        $py = $portalPos->y;
                        $pz = $portalPos->z;
                        $adjX = $targetLevel->isInWorld((int) $px + 1, (int) $py, (int) $pz)
                                && (($targetLevel->getFullBlock((int) $px + 1, (int) $py, (int) $pz) >> Block::INTERNAL_METADATA_BITS) === BlockIds::PORTAL);
                        $adjZ = $targetLevel->isInWorld((int) $px, (int) $py, (int) $pz + 1)
                                && (($targetLevel->getFullBlock((int) $px, (int) $py, (int) $pz + 1) >> Block::INTERNAL_METADATA_BITS) === BlockIds::PORTAL);
                        if ($adjX) {
                                $center = new Vector3($px + 1.0, $py, $pz + 0.5);
                        } elseif ($adjZ) {
                                $center = new Vector3($px + 0.5, $py, $pz + 1.0);
                        } else {
                                $center = $portalPos->add(0.5, 0, 0.5);
                        }
                        $spawnPos = Position::fromObject($center, $targetLevel);
                        $safeSpawn = $targetLevel->getSafeSpawn($spawnPos);

                        // Pre-generate the destination chunks so the client doesn't get stuck
                        // on the "Generating world" loading screen. We synchronously load the
                        // chunks (creating empty chunks if needed) and kick off async population
                        // for the destination chunk + its 3x3 neighbours. By the time the client
                        // receives ChangeDimensionPacket, at least the spawn chunk will exist in
                        // the server's memory and can be sent immediately on the next tick.
                        // Wrapped in try-catch: if generation fails, we still teleport — an
                        // empty chunk is better than the player getting stuck/kicked.
                        try {
                                $this->preGenerateDestinationChunks($targetLevel, (int) $safeSpawn->x, (int) $safeSpawn->y, (int) $safeSpawn->z);
                        } catch (\Throwable $e) {
                                $this->server->getLogger()->warning("Failed to pre-generate destination chunks: " . $e->getMessage());
                                $this->server->getLogger()->logException($e);
                        }

                        // CRITICAL: set the player's coordinates BEFORE calling teleport().
                        // Player::switchLevel() sends ChangeDimensionPacket and the dimension-
                        // change ACK using $this->asVector3(). Entity::setPosition() calls
                        // switchLevel() BEFORE updating $this->x/y/z, so without this pre-set
                        // the client would receive the OLD (pre-teleport) position and get
                        // stuck on the loading screen waiting for chunks at the wrong location.
                        $this->x = $safeSpawn->x;
                        $this->y = $safeSpawn->y;
                        $this->z = $safeSpawn->z;

                        $this->teleport($safeSpawn);
                } else {
                        // Fallback: world spawn (shouldn't normally happen).
                        $this->teleport($targetLevel->getSafeSpawn());
                }
        }

        /**
         * Synchronously loads (creates if missing) the destination chunk and its
         * 3x3 neighbours, and kicks off async population for any that aren't
         * populated yet. This ensures the spawn chunk exists in server memory
         * before the dimension-change packets are sent to the client.
         *
         * Without this, the client may get stuck on the "Generating world"
         * loading screen because the server can't send chunks that don't exist
         * yet, and the spawn-chunk counter never reaches the threshold needed
         * to send the PLAYER_SPAWN play-status packet.
         */
        protected function preGenerateDestinationChunks(Level $level, int $blockX, int $blockY, int $blockZ, int $chunkRadius = 1) : void{
                $centerChunkX = $blockX >> Chunk::COORD_BIT_SIZE;
                $centerChunkZ = $blockZ >> Chunk::COORD_BIT_SIZE;

                // Synchronously generate the destination chunks (terrain + population)
                // on the main thread. This ensures the chunks have real terrain and are
                // immediately sendable to the client (no loading-screen freeze).
                $this->generateChunksSynchronously($level, $centerChunkX, $centerChunkZ, $chunkRadius);
        }

        /**
         * Searches the target level for an existing Nether portal block near the given
         * coordinates. Uses a per-level cache of known portal positions first, so that
         * repeated trips through the same portal don't re-scan the world every time.
         *
         * Cache miss falls back to an expanding-ring (Chebyshev distance) scan with a
         * radius of 16 blocks. Only LOADED chunks are scanned (unloaded chunks can't
         * contain portals), and the Y range is limited to ±32 around the target Y to
         * avoid scanning the full 256-block world height for every column.
         *
         * The previous implementation used radius=128 and full-height Y scan, which
         * scanned ~17 million blocks per call and froze the main thread for 5-10
         * seconds. If the player was bouncing between dimensions (creative mode),
         * the server would permanently hang.
         *
         * @return Vector3|null Position of any portal block found, or null if none.
         */
        protected function findNetherPortal(Level $level, int $originX, int $originY, int $originZ, int $radius = 16) : ?Vector3{
                $folderName = $level->getFolderName();
                $yMin = Level::Y_MIN;
                $yMax = $level->getWorldHeight() - 1;

                // Clamp the Y search range to ±32 around the target Y (vanilla-like).
                // This limits each column scan to at most 65 blocks instead of 256.
                $scanYMin = max($yMin, $originY - 32);
                $scanYMax = min($yMax, $originY + 32);

                // 1) Fast path: consult the per-level portal cache.
                //    Validate each cached entry by re-checking the actual world state, so
                //    that portals destroyed by players don't cause us to teleport into a
                //    non-existent portal. Stale entries are pruned on the fly.
                if (isset(self::$portalCache[$folderName])) {
                        $bestDistSq = -1;
                        $bestPos = null;
                        $survived = [];
                        foreach (self::$portalCache[$folderName] as $cached) {
                                $cx = (int) $cached->x;
                                $cy = (int) $cached->y;
                                $cz = (int) $cached->z;
                                if (!$level->isInWorld($cx, $cy, $cz)) {
                                        continue; // drop stale entry
                                }
                                if (($level->getFullBlock($cx, $cy, $cz) >> Block::INTERNAL_METADATA_BITS) !== BlockIds::PORTAL) {
                                        continue; // portal no longer here, drop
                                }
                                $survived[] = $cached;
                                $distSq = ($cx - $originX) * ($cx - $originX) + ($cz - $originZ) * ($cz - $originZ);
                                if ($bestPos === null || $distSq < $bestDistSq) {
                                        $bestDistSq = $distSq;
                                        $bestPos = $cached;
                                }
                        }
                        self::$portalCache[$folderName] = $survived;
                        if ($bestPos !== null && $bestDistSq <= ($radius * $radius)) {
                                return $bestPos;
                        }
                }

                // 2) Slow path: ring scan, only scanning the perimeter of each ring.
                //    Skip columns whose chunk is NOT loaded (unloaded chunks can't
                //    contain portal blocks — getFullBlock would just return AIR).

                // Check the origin column first (ring 0).
                $originChunk = $level->getChunk($originX >> Chunk::COORD_BIT_SIZE, $originZ >> Chunk::COORD_BIT_SIZE);
                if ($originChunk !== null) {
                        for ($y = $scanYMin; $y <= $scanYMax; ++$y) {
                                if (($level->getFullBlock($originX, $y, $originZ) >> Block::INTERNAL_METADATA_BITS) === BlockIds::PORTAL) {
                                        $found = new Vector3($originX, $y, $originZ);
                                        if (!isset(self::$portalCache[$folderName])) {
                                                self::$portalCache[$folderName] = [];
                                        }
                                        self::$portalCache[$folderName][] = $found;
                                        return $found;
                                }
                        }
                }

                for ($r = 1; $r <= $radius; ++$r) {
                        for ($dx = -$r; $dx <= $r; ++$dx) {
                                for ($dz = -$r; $dz <= $r; ++$dz) {
                                        if (abs($dx) !== $r && abs($dz) !== $r) {
                                                continue;
                                        }
                                        $x = $originX + $dx;
                                        $z = $originZ + $dz;
                                        if (!$level->isInWorld($x, $scanYMin, $z)) {
                                                continue;
                                        }
                                        // Skip unloaded chunks — they can't contain portal blocks.
                                        $chunk = $level->getChunk($x >> Chunk::COORD_BIT_SIZE, $z >> Chunk::COORD_BIT_SIZE);
                                        if ($chunk === null) {
                                                continue;
                                        }
                                        for ($y = $scanYMin; $y <= $scanYMax; ++$y) {
                                                if (($level->getFullBlock($x, $y, $z) >> Block::INTERNAL_METADATA_BITS) === BlockIds::PORTAL) {
                                                        $found = new Vector3($x, $y, $z);
                                                        if (!isset(self::$portalCache[$folderName])) {
                                                                self::$portalCache[$folderName] = [];
                                                        }
                                                        self::$portalCache[$folderName][] = $found;
                                                        return $found;
                                                }
                                        }
                                }
                        }
                }

                return null;
        }

        /**
         * Builds a fresh 4-wide x 5-tall Nether portal (2x3 interior) at or near the
         * given coordinates and returns the position of the bottom interior portal block.
         *
         * The portal can be built on either the X axis (interior at x, x+1) or the
         * Z axis (interior at z, z+1). The axis with the larger available air pocket
         * is chosen, mirroring vanilla Minecraft's behaviour where the orientation
         * adapts to the surrounding terrain. If no air pocket is found near the target
         * Y on either axis, the existing blocks are simply overwritten.
         *
         * IMPORTANT: Before placing the portal, the destination chunks are SYNCHRONOUSLY
         * generated using the level's generator. This ensures the portal is embedded in
         * REAL terrain (netherrack, caves, lava) instead of floating in an empty/void
         * chunk. After generation, the portal blocks are placed on top of the terrain,
         * and the chunks are marked as generated+populated so the async PopulationTask
         * won't overwrite our portal.
         *
         * The freshly created portal is registered in the per-level portal cache so
         * subsequent trips use the fast path.
         *
         * @return Vector3 Position of the bottom-interior portal block.
         */
        protected function createNetherPortal(Level $level, int $originX, int $originY, int $originZ) : ?Vector3{
                $yMin = Level::Y_MIN;
                $yMax = $level->getWorldHeight() - 1;

                // 1) Synchronously generate the destination chunks BEFORE placing the portal.
                //    This creates real terrain (netherrack, caves, lava lakes) so the portal
                //    will be embedded in the landscape instead of floating in a void chunk.
                //    We generate a 3x3 chunk area around the target to ensure population
                //    (which depends on neighbouring chunks) works correctly.
                $this->generateChunksSynchronously($level, $originX >> Chunk::COORD_BIT_SIZE, $originZ >> Chunk::COORD_BIT_SIZE, 1);

                // 2) Now that the terrain exists, find a suitable Y for the portal by
                //    searching for a real air pocket in the generated terrain.
                //    The frame occupies 5 vertical blocks (placeY-1 .. placeY+3).
                $minY = max($yMin + 1, $originY - 8);
                $maxY = min($yMax - 4, $originY + 8);

                // Try both axes and pick the one with the largest open air pocket.
                $xAxes = $this->findPortalPlacement($level, $originX, $originY, $originZ, true, $minY, $maxY);
                $zAxes = $this->findPortalPlacement($level, $originX, $originY, $originZ, false, $minY, $maxY);

                if ($xAxes === null && $zAxes === null) {
                        // No natural air pocket found — carve one out of the terrain at the
                        // target Y. This happens in dense netherrack; the portal will still
                        // be embedded in the surrounding terrain.
                        $placeY = max($yMin + 1, min($originY, $yMax - 4));
                        $useXAxis = true;
                        // Carve a 2x5 air pocket so the portal fits.
                        $airBlock = BlockFactory::get(BlockIds::AIR);
                        for ($cy = $placeY - 1; $cy <= $placeY + 3; ++$cy) {
                                for ($cx = $originX; $cx <= $originX + 1; ++$cx) {
                                        if ($level->isInWorld($cx, $cy, $originZ)) {
                                                $level->setBlockAt($cx, $cy, $originZ, $airBlock, false, false);
                                        }
                                }
                        }
                } elseif ($xAxes === null) {
                        [$placeY,] = $zAxes;
                        $useXAxis = false;
                } elseif ($zAxes === null) {
                        [$placeY,] = $xAxes;
                        $useXAxis = true;
                } else {
                        if ($zAxes[1] > $xAxes[1]) {
                                [$placeY,] = $zAxes;
                                $useXAxis = false;
                        } else {
                                [$placeY,] = $xAxes;
                                $useXAxis = true;
                        }
                }

                $obsidian = BlockFactory::get(BlockIds::OBSIDIAN);
                $portalBlock = BlockFactory::get(BlockIds::PORTAL);
                $airBlock = BlockFactory::get(BlockIds::AIR);

                // Build the obsidian frame (4 wide x 5 tall).
                // On X axis: interior at x..x+1, z fixed; frame at x-1..x+2, z fixed.
                // On Z axis: interior at z..z+1, x fixed; frame at z-1..z+2, x fixed.
                for ($fA = -1; $fA <= 2; ++$fA) {
                        for ($fy = -1; $fy <= 3; ++$fy) {
                                if ($useXAxis) {
                                        $x = $originX + $fA;
                                        $y = $placeY + $fy;
                                        $z = $originZ;
                                } else {
                                        $x = $originX;
                                        $y = $placeY + $fy;
                                        $z = $originZ + $fA;
                                }
                                if (!$level->isInWorld($x, $y, $z)) {
                                        continue;
                                }
                                $isBorder = ($fA === -1 || $fA === 2 || $fy === -1 || $fy === 3);
                                $level->setBlockAt($x, $y, $z, $isBorder ? $obsidian : $portalBlock, false, false);
                        }
                }

                // Vanilla-like platform: clear a 4-wide (along portal axis) x 5-tall
                // x 3-deep area AROUND the portal frame so the player has room to stand
                // and walk out. This is what vanilla Minecraft does — it creates a small
                // clearing around newly generated portals.
                //
                // The cleared area is:
                //   - Along the portal axis: from -1 to +2 (4 blocks, covering the frame width)
                //   - Vertically: from -1 to +3 (5 blocks, covering the frame height)
                //   - Perpendicular to the portal axis: from -1 to +1 (3 blocks, so the
                //     player can stand on either side of the portal)
                //
                // Blocks that are part of the portal frame (obsidian) or portal interior
                // are NOT cleared — only the surrounding blocks are set to air.
                for ($cAxis = -1; $cAxis <= 2; ++$cAxis) {
                        for ($cy = -1; $cy <= 3; ++$cy) {
                                for ($cPerp = -1; $cPerp <= 1; ++$cPerp) {
                                        if ($useXAxis) {
                                                $x = $originX + $cAxis;
                                                $y = $placeY + $cy;
                                                $z = $originZ + $cPerp;
                                        } else {
                                                $x = $originX + $cPerp;
                                                $y = $placeY + $cy;
                                                $z = $originZ + $cAxis;
                                        }
                                        if (!$level->isInWorld($x, $y, $z)) {
                                                continue;
                                        }
                                        // Don't clear the frame blocks or portal interior.
                                        $isFrame = ($cAxis === -1 || $cAxis === 2 || $cy === -1 || $cy === 3) && $cPerp === 0;
                                        $isInterior = ($cAxis >= 0 && $cAxis <= 1 && $cy >= 0 && $cy <= 2) && $cPerp === 0;
                                        if ($isFrame || $isInterior) {
                                                continue;
                                        }
                                        $level->setBlockAt($x, $y, $z, $airBlock, false, false);
                                }
                        }
                }

                // Place a solid obsidian floor under the portal entrance so the player
                // doesn't fall through (vanilla does this too).
                for ($cAxis = -1; $cAxis <= 2; ++$cAxis) {
                        for ($cPerp = -1; $cPerp <= 1; ++$cPerp) {
                                if ($useXAxis) {
                                        $x = $originX + $cAxis;
                                        $y = $placeY - 2;
                                        $z = $originZ + $cPerp;
                                } else {
                                        $x = $originX + $cPerp;
                                        $y = $placeY - 2;
                                        $z = $originZ + $cAxis;
                                }
                                if (!$level->isInWorld($x, $y, $z)) {
                                        continue;
                                }
                                $level->setBlockAt($x, $y, $z, $obsidian, false, false);
                        }
                }

                $result = new Vector3($originX, $placeY, $originZ);

                // Register the freshly built portal in the per-level cache.
                $folderName = $level->getFolderName();
                if (!isset(self::$portalCache[$folderName])) {
                        self::$portalCache[$folderName] = [];
                }
                self::$portalCache[$folderName][] = $result;

                return $result;
        }

        /**
         * Synchronously generates and populates chunks on the MAIN THREAD using a
         * temporary generator instance. This is the same logic as PopulationTask but
         * runs inline instead of on a worker thread.
         *
         * This is used when creating a new Nether portal: the destination chunks must
         * contain real terrain (netherrack, caves, lava) so the portal is embedded in
         * the landscape instead of floating in a void.
         *
         * After generation, chunks are marked as generated+populated and transferred
         * to the Level, so the async PopulationTask won't re-generate them (which
         * would overwrite the portal).
         *
         * @param int $centerChunkX Center chunk X
         * @param int $centerChunkZ Center chunk Z
         * @param int $radius       Radius in chunks (1 = 3x3, 2 = 5x5)
         */
        protected function generateChunksSynchronously(Level $level, int $centerChunkX, int $centerChunkZ, int $radius = 1) : void{
                $yMin = Level::Y_MIN;
                $yMax = $level->getWorldHeight() - 1;

                // Collect the list of chunks to generate (center + radius ring).
                $chunkCoords = [];
                for ($dx = -$radius; $dx <= $radius; ++$dx) {
                        for ($dz = -$radius; $dz <= $radius; ++$dz) {
                                $chunkCoords[] = [$centerChunkX + $dx, $centerChunkZ + $dz];
                        }
                }

                // Check if all chunks are already generated+populated; if so, skip.
                $allReady = true;
                foreach ($chunkCoords as [$cx, $cz]) {
                        $chunk = $level->getChunk($cx, $cz, false);
                        if ($chunk === null || !$chunk->isGenerated() || !$chunk->isPopulated()) {
                                $allReady = false;
                                break;
                        }
                }
                if ($allReady) {
                        return;
                }

                // Create a temporary generator instance on the main thread.
                // Wrap in try-catch: if generator init or generation throws, we still
                // need the chunks to exist (even if empty) so the teleport doesn't fail.
                $manager = new SimpleChunkManager($level->getSeed(), $level->getWorldHeight());
                $generator = null;

                try {
                        $generatorName = $level->getProvider()->getLevelData()->getGenerator();
                        $generatorClass = GeneratorManager::getGenerator($generatorName);
                        $generatorOptions = $level->getProvider()->getLevelData()->getGeneratorOptions();

                        /** @var \pocketmine\level\generator\Generator $generator */
                        $generator = new $generatorClass($generatorOptions);
                        $generator->init($manager, new Random($level->getSeed()));
                } catch (\Throwable $e) {
                        $this->server->getLogger()->warning("Failed to initialize generator for level " . $level->getFolderName() . ": " . $e->getMessage());
                        $this->server->getLogger()->logException($e);
                        $generator = null;
                }

                // Step 1: Load/create each chunk and generate terrain (generateChunk).
                //         Population requires neighbours to be generated, so we generate ALL
                //         chunks first, then populate.
                $generatedChunks = [];
                foreach ($chunkCoords as [$cx, $cz]) {
                        // Try to load the chunk from disk first (in case it was partially generated).
                        $level->loadChunk($cx, $cz, true);
                        $chunk = $level->getChunk($cx, $cz, true);
                        if ($chunk === null) {
                                continue;
                        }

                        // Put the chunk into the temporary manager for the generator to operate on.
                        $manager->setChunk($cx, $cz, $chunk);

                        if ($generator !== null && !$chunk->isGenerated()) {
                                try {
                                        $generator->generateChunk($cx, $cz);
                                        $chunk = $manager->getChunk($cx, $cz);
                                        if ($chunk !== null) {
                                                $chunk->setGenerated();
                                        }
                                } catch (\Throwable $e) {
                                        $this->server->getLogger()->warning("Failed to generate chunk ($cx, $cz): " . $e->getMessage());
                                        $this->server->getLogger()->logException($e);
                                }
                        }
                        $generatedChunks[Level::chunkHash($cx, $cz)] = $manager->getChunk($cx, $cz);
                }

                // Step 2: Populate each chunk. Population places features (ores, glowstone,
                //         structures) and requires the 3x3 neighbours to be generated.
                if ($generator !== null) {
                        foreach ($chunkCoords as [$cx, $cz]) {
                                $chunk = $manager->getChunk($cx, $cz);
                                if ($chunk === null || $chunk->isPopulated()) {
                                        continue;
                                }

                                try {
                                        $generator->populateChunk($cx, $cz);
                                        $chunk = $manager->getChunk($cx, $cz);
                                        if ($chunk !== null) {
                                                $chunk->setPopulated();
                                                // Recalculate height map and lighting for the populated chunk.
                                                $chunk->recalculateHeightMap();
                                                $chunk->populateSkyLight();
                                                $chunk->setLightPopulated();
                                        }
                                } catch (\Throwable $e) {
                                        $this->server->getLogger()->warning("Failed to populate chunk ($cx, $cz): " . $e->getMessage());
                                        $this->server->getLogger()->logException($e);
                                        // Mark as populated anyway so the async PopulationTask
                                        // won't try again (and fail again) later.
                                        $chunk = $manager->getChunk($cx, $cz);
                                        if ($chunk !== null) {
                                                $chunk->setPopulated();
                                        }
                                }
                        }
                }

                // Step 3: Transfer generated chunks back to the Level.
                //         setChunk() handles entities, tiles, and chunk caching.
                foreach ($generatedChunks as $hash => $chunk) {
                        if ($chunk === null) {
                                continue;
                        }
                        Level::getXZ($hash, $cx, $cz);
                        $level->setChunk($cx, $cz, $chunk, false);
                }

                // Clean up the temporary manager.
                $manager->cleanChunks();
        }

        /**
         * Helper for createNetherPortal(): scans candidate Y values for a clean
         * 5-tall x 2-wide air pocket on the given axis, and counts how many air
         * blocks surround the chosen placement (used to choose between X and Z).
         *
         * @param bool   $useXAxis True for X-axis portal, false for Z-axis.
         * @return array{0:int,1:int}|null [placeY, surroundingAirCount] or null.
         */
        protected function findPortalPlacement(Level $level, int $originX, int $originY, int $originZ, bool $useXAxis, int $minY, int $maxY) : ?array{
                if ($minY > $maxY) {
                        return null;
                }

                $airFull = BlockIds::AIR << Block::INTERNAL_METADATA_BITS;
                $placeY = -1;
                $bestAirCount = -1;

                for ($candidateY = $minY; $candidateY <= $maxY; ++$candidateY) {
                        $ok = true;
                        $airCount = 0;
                        for ($dy = 0; $dy < 5 && $ok; ++$dy) {
                                for ($dAxis = 0; $dAxis < 2 && $ok; ++$dAxis) {
                                        if ($useXAxis) {
                                                $x = $originX + $dAxis;
                                                $y = $candidateY + $dy;
                                                $z = $originZ;
                                        } else {
                                                $x = $originX;
                                                $y = $candidateY + $dy;
                                                $z = $originZ + $dAxis;
                                        }
                                        if (!$level->isInWorld($x, $y, $z) || $level->getFullBlock($x, $y, $z) !== $airFull) {
                                                $ok = false;
                                        } else {
                                                $airCount++;
                                        }
                                }
                        }
                        if ($ok && $airCount > $bestAirCount) {
                                $bestAirCount = $airCount;
                                $placeY = $candidateY;
                        }
                }

                if ($placeY < 0) {
                        return null;
                }

                // Also count surrounding air just outside the frame (for axis comparison).
                $extraAir = 0;
                for ($dy = -1; $dy <= 3; ++$dy) {
                        foreach ([ -1, 2 ] as $side) {
                                if ($useXAxis) {
                                        $x = $originX + $side;
                                        $y = $placeY + $dy;
                                        $z = $originZ;
                                } else {
                                        $x = $originX;
                                        $y = $placeY + $dy;
                                        $z = $originZ + $side;
                                }
                                if ($level->isInWorld($x, $y, $z) && $level->getFullBlock($x, $y, $z) === $airFull) {
                                        $extraAir++;
                                }
                        }
                }

                return [$placeY, $bestAirCount + $extraAir];
        }

        public function isOnFire() : bool
        {
                return $this->fireTicks > 0;
        }

        public function setOnFire(int $seconds) : void
        {
                $ticks = $seconds * 20;
                if ($ticks > $this->getFireTicks()) {
                        $this->setFireTicks($ticks);
                }

                $this->setGenericFlag(self::DATA_FLAG_ONFIRE, $this->isOnFire());
        }

        public function getFireTicks() : int
        {
                return $this->fireTicks;
        }

        /**
         * @throws InvalidArgumentException
         */
        public function setFireTicks(int $fireTicks) : void
        {
                if($fireTicks < 0){
                        throw new InvalidArgumentException("Fire ticks cannot be negative");
                }

                //Since the max value is not externally obvious or intuitive, many plugins use this without being aware that
                //reasonably large values are not accepted. We even have such usages within PM itself. It doesn't make sense
                //to force all those calls to be aware of this limitation, as it's not a functional limit but a limitation of
                //the Mojang save format. Truncating this to the max acceptable value is the next best thing we can do.
                $fireTicks = min($fireTicks, INT16_MAX);

                if(!$this->isFireProof()) {
                        $this->fireTicks = $fireTicks;
                }
        }

        public function extinguish() : void
        {
                $this->fireTicks = 0;
                $this->setGenericFlag(self::DATA_FLAG_ONFIRE, false);
        }

        public function isFireProof() : bool
        {
                return false;
        }

        protected function doOnFireTick(int $tickDiff = 1) : bool
        {
                if($this->isFireProof() && $this->isOnFire()){
                        $this->extinguish();
                        return false;
                }

                $this->fireTicks -= $tickDiff;

                if (($this->fireTicks % 20 === 0) || $tickDiff > 20) {
                        $this->dealFireDamage();
                }

                if (!$this->isOnFire()) {
                        $this->extinguish();
                } else {
                        return true;
                }

                return false;
        }

        /**
         * Called to deal damage to entities when they are on fire.
         */
        protected function dealFireDamage() : void
        {
                $ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FIRE_TICK, 1);
                $this->attack($ev);
        }

        public function canCollideWith(Entity $entity) : bool
        {
                return !$this->justCreated && $entity !== $this;
        }

        public function canBeCollidedWith() : bool
        {
                return $this->isAlive();
        }

        protected function updateMovement(bool $teleport = false) : void
        {
                $diffPosition = ($this->x - $this->lastX) ** 2 + ($this->y - $this->lastY) ** 2 + ($this->z - $this->lastZ) ** 2;
                $diffRotation = ($this->yaw - $this->lastYaw) ** 2 + ($this->pitch - $this->lastPitch) ** 2;

                if ($this->headYaw !== null) {
                        $diffRotation += ($this->headYaw - $this->lastHeadYaw) ** 2;
                }

                $diffMotion = $this->motion->subtractVector($this->lastMotion)->lengthSquared();

                $still = $this->motion->lengthSquared() == 0.0;
                $wasStill = $this->lastMotion->lengthSquared() == 0.0;
                if ($wasStill !== $still && !($this instanceof Mob)) {
                        //TODO: hack for client-side AI interference: prevent client sided movement when motion is 0
                        $this->setImmobile($still);
                }

                if ($teleport || $diffPosition > 0.0001 || $diffRotation > 1.0 || (!$wasStill && $still)) {
                        $this->lastX = $this->x;
                        $this->lastY = $this->y;
                        $this->lastZ = $this->z;

                        $this->lastYaw = $this->yaw;
                        $this->lastPitch = $this->pitch;
                        $this->lastHeadYaw = $this->headYaw ?? 0;

                        $this->broadcastMovement($teleport);
                }

                if ($diffMotion > 0.0025 || $wasStill !== $still) { //0.05 ** 2
                        $this->lastMotion = clone $this->motion;

                        $this->broadcastMotion();
                }
        }

        public function getOffsetPosition(Vector3 $vector3) : Vector3{
                return new Vector3($vector3->x, $vector3->y + $this->baseOffset, $vector3->z);
        }

        protected function broadcastMovement(bool $teleport = false) : void
        {
                $pk = new MoveActorAbsolutePacket();
                $pk->entityRuntimeId = $this->id;
                $pk->position = $this->getOffsetPosition($this);
                $pk->pitch = $this->pitch;
                $pk->yaw = $this->yaw;
                $pk->headYaw = $this->yaw;

                if ($teleport) {
                        $pk->flags |= MoveActorAbsolutePacket::FLAG_TELEPORT;
                }

                if ($this->onGround) {
                        $pk->flags |= MoveActorAbsolutePacket::FLAG_GROUND;
                }

                $this->level->broadcastPacketToViewers($this, $pk);
        }

        protected function broadcastMotion() : void
        {
                $pk = new SetActorMotionPacket();
                $pk->entityRuntimeId = $this->id;
                $pk->motion = $this->getMotion();
                $pk->tick = 0;

                $this->level->broadcastPacketToViewers($this, $pk);
        }

        /**
         * Pushes the other entity
         */
        public function applyEntityCollision(Entity $entity) : void
        {
                if (!$this->isRiding() && !$entity->isRiding()) {
                        if (!($entity instanceof Player && $entity->isSpectator())) {
                                $d0 = $entity->x - $this->x;
                                $d1 = $entity->z - $this->z;
                                $d2 = sqrt($d0 * $d0 + $d1 * $d1);

                                if ($d2 > 0) {
                                        $d0 /= $d2;
                                        $d1 /= $d2;
                                        $d3 = min(1, 1 / $d2);

                                        $entity->setMotion($entity->getMotion()->add($d0 * $d3 * 0.05, 0, $d1 * $d3 * 0.05));
                                }
                        }
                }
        }

        protected function applyDragBeforeGravity() : bool
        {
                return false;
        }

        protected function applyGravity() : void
        {
                $this->motion->y -= $this->gravity;
        }

        protected function tryChangeMovement() : void
        {
                $friction = 1 - $this->drag;

                if ($this->applyDragBeforeGravity()) {
                        $this->motion->y *= $friction;
                }

                $this->applyGravity();

                if (!$this->applyDragBeforeGravity()) {
                        $this->motion->y *= $friction;
                }

                if ($this->onGround) {
                        $friction *= $this->level->getBlockAt((int) floor($this->x), (int) floor($this->y - 1), (int) floor($this->z))->getFrictionFactor();
                }

                $this->motion->x *= $friction;
                $this->motion->z *= $friction;
        }

        protected function checkObstruction(float $x, float $y, float $z) : bool
        {
                $level = $this->getLevel();
                if (count($level->getCollisionCubes($this, $this->getBoundingBox(), false)) === 0) {
                        return false;
                }

                $floorX = (int) floor($x);
                $floorY = (int) floor($y);
                $floorZ = (int) floor($z);

                $diffX = $x - $floorX;
                $diffY = $y - $floorY;
                $diffZ = $z - $floorZ;

                if ($level->getBlockAt($floorX, $floorY, $floorZ)->isSolid()) {
                        $westNonSolid = !$level->getBlockAt($floorX - 1, $floorY, $floorZ)->isSolid();
                        $eastNonSolid = !$level->getBlockAt($floorX + 1, $floorY, $floorZ)->isSolid();
                        $downNonSolid = !$level->getBlockAt($floorX, $floorY - 1, $floorZ)->isSolid();
                        $upNonSolid = !$level->getBlockAt($floorX, $floorY + 1, $floorZ)->isSolid();
                        $northNonSolid = !$level->getBlockAt($floorX, $floorY, $floorZ - 1)->isSolid();
                        $southNonSolid = !$level->getBlockAt($floorX, $floorY, $floorZ + 1)->isSolid();

                        $direction = -1;
                        $limit = 9999;

                        if ($westNonSolid) {
                                $limit = $diffX;
                                $direction = Facing::WEST;
                        }

                        if ($eastNonSolid && 1 - $diffX < $limit) {
                                $limit = 1 - $diffX;
                                $direction = Facing::EAST;
                        }

                        if ($downNonSolid && $diffY < $limit) {
                                $limit = $diffY;
                                $direction = Facing::DOWN;
                        }

                        if ($upNonSolid && 1 - $diffY < $limit) {
                                $limit = 1 - $diffY;
                                $direction = Facing::UP;
                        }

                        if ($northNonSolid && $diffZ < $limit) {
                                $limit = $diffZ;
                                $direction = Facing::NORTH;
                        }

                        if ($southNonSolid && 1 - $diffZ < $limit) {
                                $direction = Facing::SOUTH;
                        }

                        $force = Utils::getRandomFloat() * 0.2 + 0.1;

                        if ($direction === Facing::WEST) {
                                $this->motion->x = -$force;

                                return true;
                        }

                        if ($direction === Facing::EAST) {
                                $this->motion->x = $force;

                                return true;
                        }

                        if ($direction === Facing::DOWN) {
                                $this->motion->y = -$force;

                                return true;
                        }

                        if ($direction === Facing::UP) {
                                $this->motion->y = $force;

                                return true;
                        }

                        if ($direction === Facing::NORTH) {
                                $this->motion->z = -$force;

                                return true;
                        }

                        if ($direction === Facing::SOUTH) {
                                $this->motion->z = $force;

                                return true;
                        }
                }

                return false;
        }

        public function getDirection() : ?int
        {
                $rotation = fmod($this->yaw - 90, 360);
                if ($rotation < 0) {
                        $rotation += 360.0;
                }
                if ((0 <= $rotation && $rotation < 45) || (315 <= $rotation && $rotation < 360)) {
                        return 2; //North
                } elseif (45 <= $rotation && $rotation < 135) {
                        return 3; //East
                } elseif (135 <= $rotation && $rotation < 225) {
                        return 0; //South
                } elseif (225 <= $rotation && $rotation < 315) {
                        return 1; //West
                } else {
                        return null;
                }
        }

        public function getDirectionVector() : Vector3
        {
                $y = -sin(deg2rad($this->pitch));
                $xz = cos(deg2rad($this->pitch));
                $x = -$xz * sin(deg2rad($this->yaw));
                $z = $xz * cos(deg2rad($this->yaw));

                return (new Vector3($x, $y, $z))->normalize();
        }

        public function getDirectionPlane() : Vector2
        {
                return (new Vector2(-cos(deg2rad($this->yaw) - M_PI_2), -sin(deg2rad($this->yaw) - M_PI_2)))->normalize();
        }

        public function onUpdate(int $currentTick) : bool
        {
                if ($this->closed) {
                        return false;
                }

                $tickDiff = $currentTick - $this->lastUpdate;
                if ($tickDiff <= 0) {
                        if (!$this->justCreated) {
                                $this->server->getLogger()->debug("Expected tick difference of at least 1, got $tickDiff for " . get_class($this));
                        }

                        return true;
                }

                $this->lastUpdate = $currentTick;

                if (!$this->isAlive()) {
                        if (!$this->isKilled) {
                                $this->isKilled = true;
                                $this->kill();
                        } elseif ($this->onDeathUpdate($tickDiff)) {
                                $this->flagForDespawn();
                        }

                        return true;
                }

                $this->timings->startTiming();

                if ($this->hasMovementUpdate()) {
                        $this->onMovementUpdate();

                        $this->forceMovementUpdate = false;
                        $this->updateMovement();
                }

                Timings::$entityBaseTick->startTiming();
                $hasUpdate = $this->entityBaseTick($tickDiff);
                Timings::$entityBaseTick->stopTiming();

                $this->timings->stopTiming();

                return ($hasUpdate || $this->hasMovementUpdate());
        }

        protected function onMovementUpdate() : void
        {
                $this->tryChangeMovement();

                $this->checkMotion();

                if ($this->motion->x != 0 || $this->motion->y != 0 || $this->motion->z != 0) {
                        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
                }
        }

        protected function checkMotion() : void
        {
                if (abs($this->motion->x) <= self::MOTION_THRESHOLD) {
                        $this->motion->x = 0;
                }
                if (abs($this->motion->y) <= self::MOTION_THRESHOLD) {
                        $this->motion->y = 0;
                }
                if (abs($this->motion->z) <= self::MOTION_THRESHOLD) {
                        $this->motion->z = 0;
                }
        }

        final public function scheduleUpdate() : void
        {
                if ($this->closed) {
                        throw new InvalidStateException("Cannot schedule update on garbage entity " . get_class($this));
                }
                $this->level->scheduleEntityUpdate($this);
        }

        public function shouldAlwaysUpdate() : bool{
                return false;
        }

        public function onNearbyBlockChange() : void
        {
                $this->setForceMovementUpdate();
                $this->scheduleUpdate();
        }

        /**
         * Called when a random update is performed on the chunk the entity is in. This happens when the chunk is within the
         * ticking chunk range of a player (or chunk loader).
         */
        public function onRandomUpdate() : void
        {
                $this->scheduleUpdate();
        }

        /**
         * Flags the entity as needing a movement update on the next tick. Setting this forces a movement update even if the
         * entity's motion is zero. Used to trigger movement updates when blocks change near entities.
         */
        final public function setForceMovementUpdate(bool $value = true) : void
        {
                $this->forceMovementUpdate = $value;

                $this->blocksAround = null;
        }

        /**
         * Returns whether the entity needs a movement update on the next tick.
         */
        public function hasMovementUpdate() : bool
        {
                return (
                        $this->forceMovementUpdate ||
                        $this->motion->x != 0 ||
                        $this->motion->y != 0 ||
                        $this->motion->z != 0 ||
                        !$this->onGround
                );
        }

        public function canTriggerWalking() : bool
        {
                return true;
        }

        public function canBePushed() : bool
        {
                return false;
        }

        public function resetFallDistance() : void
        {
                $this->fallDistance = 0.0;
        }

        protected function updateFallState(float $distanceThisTick, bool $onGround) : void
        {
                if ($onGround) {
                        if ($this->fallDistance > 0) {
                                $ev = new EntityFallEvent($this, $this->fallDistance);
                                $ev->call();
                                if (!$ev->isCancelled()) {
                                        $block = $this->level->getBlockAt($this->getFloorX(), (int) floor($this->y - 0.2), $this->getFloorZ());
                                        if ($block->isSolid()) {
                                                $block->onEntityFallenUpon($this, $this->fallDistance);
                                        }

                                        $this->fall($this->fallDistance);
                                }

                                $this->resetFallDistance();
                        }
                } elseif ($distanceThisTick < $this->fallDistance) {
                        //we've fallen some distance (distanceThisTick is negative)
                        //or we ascended back towards where fall distance was measured from initially (distanceThisTick is positive but less than existing fallDistance)
                        $this->fallDistance -= $distanceThisTick;
                } else {
                        //we ascended past the apex where fall distance was originally being measured from
                        //reset it so it will be measured starting from the new, higher position
                        $this->fallDistance = 0;
                }
        }

        public function mountEntity(Entity $entity, int $seatNumber = 0, bool $causedByRider = true) : bool
        {
                if ($this->getRidingEntity() === null && $entity !== $this && count($entity->passengers) < $entity->getSeatCount()) {
                        if (!isset($entity->passengers[$seatNumber])) {
                                ($ev = new EntityMountEvent($entity, $this, $seatNumber, $causedByRider))->call();
                                if ($ev->isCancelled()) {
                                        return false;
                                }

                                if ($seatNumber === 0) {
                                        $entity->setRiddenByEntity($this);

                                        $this->setRiding(true);
                                        $entity->setGenericFlag(self::DATA_FLAG_WASD_CONTROLLED, true);
                                }

                                $this->setRotation($entity->yaw, $entity->pitch);
                                $this->setRidingEntity($entity);

                                $entity->passengers[$seatNumber] = $this->getId();

                                $this->propertyManager->setVector3(self::DATA_RIDER_SEAT_POSITION, $entity->getRiderSeatPosition($seatNumber)->add(0, $this->getMountedYOffset(), 0));
                                $this->propertyManager->setByte(self::DATA_CONTROLLING_RIDER_SEAT_NUMBER, $seatNumber);

                                $entity->sendLink($entity->getViewers(), $this->getId(), EntityLink::TYPE_RIDER, $causedByRider);

                                $entity->onRiderMount($this);

                                return true;
                        }
                }
                return false;
        }

        public function onRiderMount(Entity $entity) : void
        {

        }

        public function onRiderLeave(Entity $entity) : void
        {

        }

        /**
         * @param Player[] $targets
         */
        public function sendLink(array $targets, int $entityId, int $type = EntityLink::TYPE_RIDER, bool $immediate = false, bool $causedByRider = true, float $vehicleAngularVelocity = 0.0) : void
        {
                $pk = new SetActorLinkPacket();
                $pk->link = new EntityLink($this->id, $entityId, $type, $immediate, $causedByRider, $vehicleAngularVelocity);

                $this->server->broadcastPacket($targets, $pk);
        }

        public function getMountedYOffset() : float
        {
                return $this->height * 0.65;
        }

        public function dismountEntity(bool $immediate = false) : bool
        {
                if ($this->getRidingEntity() !== null) {
                        $entity = $this->getRidingEntity();

                        ($ev = new EntityDismountEvent($entity, $this, $immediate))->call();
                        if ($ev->isCancelled()) {
                                return false;
                        }

                        unset($entity->passengers[$this->propertyManager->getByte(self::DATA_CONTROLLING_RIDER_SEAT_NUMBER)]);

                        if ($entity->getRiddenByEntity() === $this) {
                                $entity->setRiddenByEntity(null);

                                $this->entityRiderYawDelta = 0;
                                $this->entityRiderPitchDelta = 0;

                                $this->setRiding(false);
                                $entity->setGenericFlag(Entity::DATA_FLAG_WASD_CONTROLLED, false);
                        }

                        $this->propertyManager->removeProperty(self::DATA_RIDER_SEAT_POSITION);
                        $this->propertyManager->removeProperty(self::DATA_CONTROLLING_RIDER_SEAT_NUMBER);

                        $this->setRidingEntity(null);

                        $entity->sendLink($entity->getViewers(), $this->getId(), EntityLink::TYPE_REMOVE, $immediate);

                        $entity->onRiderLeave($this);

                        return true;
                }
                return false;
        }

        public function getRiderSeatPosition(int $seatNumber = 0) : Vector3
        {
                return new Vector3(0, $this->getEyeHeight(), 0);
        }

        public function getSeatCount() : int
        {
                return 1;
        }

        public function updateRiderPosition() : void
        {
                if ($this->getRiddenByEntity() !== null) {
                        $this->getRiddenByEntity()->setPosition($this->addVector($this->getRiderSeatPosition()));
                }
        }

        public function updateRidden() : void
        {
                if ($this->getRidingEntity() === null) {
                        return;
                }

                if ($this->getRidingEntity()->isClosed()) {
                        $this->ridingEid = null;
                } else {
                        $this->resetMotion();

                        if (!($this instanceof Player)) {
                                $this->getRidingEntity()->updateRiderPosition();
                        }
                        $this->entityRiderYawDelta += $this->yaw - $this->lastYaw;

                        for ($this->entityRiderPitchDelta += $this->pitch - $this->lastPitch; $this->entityRiderYawDelta >= 180; $this->entityRiderYawDelta -= 360) {
                                //empty
                        }

                        while ($this->entityRiderYawDelta < -180) {
                                $this->entityRiderYawDelta += 360;
                        }

                        while ($this->entityRiderPitchDelta >= 180) {
                                $this->entityRiderPitchDelta -= 360;
                        }

                        while ($this->entityRiderPitchDelta < -180) {
                                $this->entityRiderPitchDelta += 360;
                        }

                        $d0 = $this->entityRiderYawDelta * 0.5;
                        $d1 = $this->entityRiderPitchDelta * 0.5;
                        $f = 10;

                        $d0 = ($d0 > $f) ? $f : (($d0 < -$f) ? -$f : $d0);
                        $d1 = ($d1 > $f) ? $f : (($d1 < -$f) ? -$f : $d1);

                        $this->entityRiderYawDelta -= $d0;
                        $this->entityRiderPitchDelta -= $d1;
                }
        }

        /**
         * Called when a falling entity hits the ground.
         */
        public function fall(float $fallDistance) : void
        {
                if ($this->getRidingEntity() instanceof Entity) {
                        $this->getRidingEntity()->fall($fallDistance);
                }
        }

        public function getEyeHeight() : float
        {
                return $this->eyeHeight;
        }

        public function moveFlying(float $strafe, float $forward, float $friction) : bool
        {
                $f = $strafe * $strafe + $forward * $forward;
                if ($f >= self::MOTION_THRESHOLD) {
                        $f = sqrt($f);

                        if ($f < 1) {
                                $f = 1;
                        }

                        $f = $friction / $f;
                        $strafe *= $f;
                        $forward *= $f;

                        $f1 = sin($this->yaw * pi() / 180);
                        $f2 = cos($this->yaw * pi() / 180);

                        $this->motion->x += $strafe * $f2 - $forward * $f1;
                        $this->motion->z += $forward * $f2 + $strafe * $f1;

                        return true;
                }

                return false;
        }

        public function onCollideWithPlayer(Player $player) : void
        {
                if ($this->canBePushed() && $this->distanceSquared($player) <= 0.08){
                        $player->applyEntityCollision($this);
                }
        }

        public function onCollideWithEntity(Entity $entity) : void
        {
                if ($this->canBePushed()) {
                        $entity->applyEntityCollision($this);
                }
        }

        public function isUnderwater() : bool
        {
                $block = $this->level->getBlockAt((int) floor($this->x), (int) floor($y = ($this->y + $this->getEyeHeight())), (int) floor($this->z));

                if ($block instanceof Water) {
                        $f = ($block->y + 1) - ($block->getFluidHeightPercent() - 0.1111111);
                        return $y < $f;
                }

                return false;
        }

        public function isWet() : bool
        {
                // TODO: check weather
                return $this->isInsideOfWater();
        }

        public function isInsideOfSolid() : bool
        {
                $block = $this->level->getBlockAt((int) floor($this->x), (int) floor($y = ($this->y + $this->getEyeHeight())), (int) floor($this->z));

                return $block->isSolid() && !$block->isTransparent() && $block->collidesWithBB($this->getBoundingBox());
        }

        public function isInsideOfLava() : bool
        {
                $block = $this->level->getBlockAt((int) floor($this->x), (int) floor($this->y), (int) floor($this->z));

                return $block instanceof Lava;
        }

        public function isInsideOfWater() : bool
        {
                $block = $this->level->getBlockAt((int) floor($this->x), (int) floor($this->y), (int) floor($this->z));

                return $block instanceof Water;
        }

        public function fastMove(float $dx, float $dy, float $dz) : bool
        {
                $this->blocksAround = null;

                if ($dx == 0 && $dz == 0 && $dy == 0) {
                        return true;
                }

                Timings::$entityMove->startTiming();

                $newBB = $this->boundingBox->offsetCopy($dx, $dy, $dz);

                $list = $this->level->getCollisionCubes($this, $newBB, false);

                if (count($list) === 0) {
                        $this->boundingBox = $newBB;
                }

                $this->x = ($this->boundingBox->minX + $this->boundingBox->maxX) / 2;
                $this->y = $this->boundingBox->minY - $this->ySize;
                $this->z = ($this->boundingBox->minZ + $this->boundingBox->maxZ) / 2;

                $this->checkChunks();

                if (!$this->onGround || $dy != 0) {
                        $bb = clone $this->boundingBox;
                        $bb->minY -= 0.75;
                        $this->onGround = false;

                        if (count($this->level->getCollisionBlocks($bb)) > 0) {
                                $this->onGround = true;
                        }
                }
                $this->isCollided = $this->onGround;
                $this->updateFallState($dy, $this->onGround);

                Timings::$entityMove->stopTiming();

                return true;
        }

        public function move(float $dx, float $dy, float $dz) : void{
                $this->blocksAround = null;

                if ($dx == 0 && $dz == 0 && $dy == 0) {
                        return;
                }

                Timings::$entityMove->startTiming();
                Timings::$entityMoveCollision->startTiming();

                $wantedX = $dx;
                $wantedY = $dy;
                $wantedZ = $dz;

                if ($this->keepMovement) {
                        $this->boundingBox->offset($dx, $dy, $dz);
                } else {
                        $this->ySize *= self::STEP_CLIP_MULTIPLIER;

                        $moveBB = clone $this->boundingBox;

                        assert(abs($dx) <= 20 && abs($dy) <= 20 && abs($dz) <= 20, "Movement distance is excessive: dx=$dx, dy=$dy, dz=$dz");

                        $list = $this->level->getBlockCollisionBoxes($moveBB->addCoord($dx, $dy, $dz));

                        foreach ($list as $bb) {
                                $dy = $bb->calculateYOffset($moveBB, $dy);
                        }

                        $moveBB->offset(0, $dy, 0);

                        $fallingFlag = ($this->onGround || ($dy != $wantedY && $wantedY < 0));

                        foreach ($list as $bb) {
                                $dx = $bb->calculateXOffset($moveBB, $dx);
                        }

                        $moveBB->offset($dx, 0, 0);

                        foreach ($list as $bb) {
                                $dz = $bb->calculateZOffset($moveBB, $dz);
                        }

                        $moveBB->offset(0, 0, $dz);

                        if ($this->stepHeight > 0 && $fallingFlag && ($wantedX != $dx || $wantedZ != $dz)) {
                                $cx = $dx;
                                $cy = $dy;
                                $cz = $dz;
                                $dx = $wantedX;
                                $dy = $this->stepHeight;
                                $dz = $wantedZ;

                                $stepBB = clone $this->boundingBox;

                                $list = $this->level->getCollisionCubes($this, $stepBB->addCoord($dx, $dy, $dz), false);
                                foreach ($list as $bb) {
                                        $dy = $bb->calculateYOffset($stepBB, $dy);
                                }

                                $stepBB->offset(0, $dy, 0);

                                foreach ($list as $bb) {
                                        $dx = $bb->calculateXOffset($stepBB, $dx);
                                }

                                $stepBB->offset($dx, 0, 0);

                                foreach ($list as $bb) {
                                        $dz = $bb->calculateZOffset($stepBB, $dz);
                                }

                                $stepBB->offset(0, 0, $dz);

                                $reverseDY = -$dy;
                                foreach ($list as $bb) {
                                        $reverseDY = $bb->calculateYOffset($stepBB, $reverseDY);
                                }
                                $dy += $reverseDY;
                                $stepBB->offset(0, $reverseDY, 0);

                                if (($cx ** 2 + $cz ** 2) >= ($dx ** 2 + $dz ** 2)) {
                                        $dx = $cx;
                                        $dy = $cy;
                                        $dz = $cz;
                                } else {
                                        $moveBB = $stepBB;
                                        $this->ySize += $dy;
                                }
                        }

                        $this->boundingBox = $moveBB;
                }
                Timings::$entityMoveCollision->stopTiming();

                $this->x = ($this->boundingBox->minX + $this->boundingBox->maxX) / 2;
                $this->y = $this->boundingBox->minY - $this->ySize;
                $this->z = ($this->boundingBox->minZ + $this->boundingBox->maxZ) / 2;

                $this->checkChunks();
                $this->checkBlockCollision();
                $this->checkEntityCollision();
                $this->checkGroundState($wantedX, $wantedY, $wantedZ, $dx, $dy, $dz);
                $this->updateFallState($dy, $this->onGround);

                if ($wantedX != $dx) {
                        $this->motion->x = 0;
                }

                if ($wantedY != $dy) {
                        $this->motion->y = 0;
                }

                if ($wantedZ != $dz) {
                        $this->motion->z = 0;
                }

                //TODO: vehicle collision events (first we need to spawn them!)

                Timings::$entityMove->stopTiming();
        }

        protected function checkGroundState(float $wantedX, float $wantedY, float $wantedZ, float $dx, float $dy, float $dz) : void
        {
                $this->isCollidedVertically = $wantedY !== $dy;
                $this->isCollidedHorizontally = ($wantedX !== $dx || $wantedZ !== $dz);
                $this->isCollided = ($this->isCollidedHorizontally || $this->isCollidedVertically);
                $this->onGround = ($wantedY !== $dy && $wantedY < 0);
        }

        /**
         * @return Block[]
         * @deprecated WARNING: Despite what its name implies, this function DOES NOT return all the blocks around the entity.
         * Instead, it returns blocks which have reactions for an entity intersecting with them.
         */
        public function getBlocksAround() : array
        {
                if ($this->blocksAround === null) {
                        $inset = 0.001; //Offset against floating-point errors

                        $minX = (int) floor($this->boundingBox->minX + $inset);
                        $minY = (int) floor($this->boundingBox->minY + $inset);
                        $minZ = (int) floor($this->boundingBox->minZ + $inset);
                        $maxX = (int) floor($this->boundingBox->maxX - $inset);
                        $maxY = (int) floor($this->boundingBox->maxY - $inset);
                        $maxZ = (int) floor($this->boundingBox->maxZ - $inset);

                        $this->blocksAround = [];

                        for ($z = $minZ; $z <= $maxZ; ++$z) {
                                for ($x = $minX; $x <= $maxX; ++$x) {
                                        for ($y = $minY; $y <= $maxY; ++$y) {
                                                $block = $this->level->getBlockAt($x, $y, $z);
                                                if ($block->hasEntityCollision()) {
                                                        $this->blocksAround[] = $block;
                                                }
                                        }
                                }
                        }
                }

                return $this->blocksAround;
        }

        /**
         * Returns whether this entity can be moved by currents in liquids.
         */
        public function canBeMovedByCurrents() : bool
        {
                return true;
        }

        protected function checkBlockCollision() : void
        {
                $vector = new Vector3(0, 0, 0);

                foreach ($this->getBlocksAround() as $block) {
                        $block->onEntityCollide($this);
                        $block->addVelocityToEntity($this, $vector);
                }

                if ($this instanceof Living) {
                        $down = $this->level->getBlockAt($this->getFloorX(), $this->getFloorY() - 1, $this->getFloorZ());
                        if ($down->hasEntityCollision()) {
                                $down->onEntityCollideUpon($this);
                        }

                        $this->setInPortal($this->level->getBlockAt($this->getFloorX(), $this->getFloorY(), $this->getFloorZ()) instanceof NetherPortal);
                }

                if ($vector->lengthSquared() > 0) {
                        $vector = $vector->normalize();
                        $d = 0.014;
                        $this->motion->x += $vector->x * $d;
                        $this->motion->y += $vector->y * $d;
                        $this->motion->z += $vector->z * $d;
                }
        }

        protected function checkEntityCollision() : void
        {
                if ($this->canBePushed()) {
                        foreach ($this->level->getCollidingEntities($this->getBoundingBox()->expandedCopy(0.2, 0, 0.2), $this) as $e) {
                                $this->onCollideWithEntity($e);
                        }
                }
        }

        public function getPosition() : Position
        {
                return $this->asPosition();
        }

        public function getLocation() : Location
        {
                return $this->asLocation();
        }

        public function setPosition(Vector3 $pos) : bool
        {
                if ($this->closed) {
                        return false;
                }

                if ($pos instanceof Position && $pos->level !== null && $pos->level !== $this->level) {
                        if (!$this->switchLevel($pos->getLevel())) {
                                return false;
                        }
                }

                $this->x = $pos->x;
                $this->y = $pos->y;
                $this->z = $pos->z;

                $this->recalculateBoundingBox();

                $this->blocksAround = null;

                $this->checkChunks();

                return true;
        }

        public function setRotation(float $yaw, float $pitch) : void
        {
                $this->yaw = $yaw;
                $this->pitch = $pitch;
                $this->scheduleUpdate();
        }

        public function setPositionAndRotation(Vector3 $pos, float $yaw, float $pitch) : bool
        {
                if ($this->setPosition($pos)) {
                        $this->setRotation($yaw, $pitch);

                        return true;
                }

                return false;
        }

        protected function checkChunks() : void
        {
                $chunkX = $this->getFloorX() >> Chunk::COORD_BIT_SIZE;
                $chunkZ = $this->getFloorZ() >> Chunk::COORD_BIT_SIZE;
                if ($this->chunk === null || ($this->chunk->getX() !== $chunkX || $this->chunk->getZ() !== $chunkZ)) {
                        if ($this->chunk !== null) {
                                $this->chunk->removeEntity($this);
                        }
                        $this->chunk = $this->level->getChunk($chunkX, $chunkZ, true);

                        if (!$this->justCreated) {
                                $newChunk = $this->level->getViewersForPosition($this);
                                foreach ($this->hasSpawned as $player) {
                                        $id = spl_object_id($player);
                                        if (!isset($newChunk[$id])) {
                                                $this->despawnFrom($player);
                                        } else {
                                                unset($newChunk[$id]);
                                        }
                                }
                                foreach ($newChunk as $player) {
                                        $this->spawnTo($player);
                                }
                        }

                        if ($this->chunk === null) {
                                return;
                        }

                        $this->chunk->addEntity($this);
                }
        }

        protected function resetLastMovements() : void
        {
                list($this->lastX, $this->lastY, $this->lastZ) = [$this->x, $this->y, $this->z];
                list($this->lastYaw, $this->lastPitch) = [$this->yaw, $this->pitch];
                $this->lastMotion = clone $this->motion;
        }

        public function getMotion() : Vector3
        {
                return clone $this->motion;
        }

        public function getSpeed() : Vector3
        {
                return $this->getMotion();
        }

        public function setMotion(Vector3 $motion) : bool
        {
                if (!$this->justCreated) {
                        $ev = new EntityMotionEvent($this, $motion);
                        $ev->call();
                        if ($ev->isCancelled()) {
                                return false;
                        }
                }

                $this->motion = clone $motion;

                if (!$this->justCreated) {
                        $this->updateMovement();
                }

                return true;
        }

        public function resetMotion() : void
        {
                $this->motion = new Vector3(0, 0, 0);
        }

        /**
         * Adds the given values to the entity's motion vector.
         */
        public function addMotion(float $x, float $y, float $z) : void
        {
                $this->motion->x += $x;
                $this->motion->y += $y;
                $this->motion->z += $z;
        }

        public function playSound(string $sound, float $volume = 1.0, float $pitch = 1.0, array $targets = null) : void
        {
                $this->level->addSound(new PlaySound($this, $sound, $volume, $pitch), $targets ?? null);
        }

        public function stopSound(string $sound, bool $stopAll = false, array $targets = null) : void
        {
                $pk = new StopSoundPacket();
                $pk->soundName = $sound;
                $pk->stopAll = $stopAll;

                $this->server->broadcastPacket($targets ?? $this->level->getViewersForPosition($this), $pk);
        }

        public function isOnGround() : bool
        {
                return $this->onGround;
        }

        /**
         * @param Vector3|Position|Location $pos
         */
        public function teleport(Vector3 $pos, ?float $yaw = null, ?float $pitch = null) : bool
        {
                if ($pos instanceof Location) {
                        $yaw = $yaw ?? $pos->yaw;
                        $pitch = $pitch ?? $pos->pitch;
                }
                $from = Position::fromObject($this, $this->level);
                $to = Position::fromObject($pos, $pos instanceof Position ? $pos->getLevel() : $this->level);
                $ev = new EntityTeleportEvent($this, $from, $to);
                $ev->call();
                if ($ev->isCancelled()) {
                        return false;
                }
                $this->ySize = 0;
                $pos = $ev->getTo();

                $this->setMotion(new Vector3(0, 0, 0));
                $this->dismountEntity(true);
                if ($this->setPositionAndRotation($pos, $yaw ?? $this->yaw, $pitch ?? $this->pitch)) {
                        $this->resetFallDistance();
                        $this->setForceMovementUpdate();

                        $this->updateMovement(true);

                        return true;
                }

                return false;
        }

        protected function switchLevel(Level $targetLevel) : bool
        {
                if ($this->closed) {
                        return false;
                }

                if ($this->isValid()) {
                        $ev = new EntityLevelChangeEvent($this, $this->level, $targetLevel);
                        $ev->call();
                        if ($ev->isCancelled()) {
                                return false;
                        }

                        $this->dismountEntity(true);

                        $this->level->removeEntity($this);
                        if ($this->chunk !== null) {
                                $this->chunk->removeEntity($this);
                        }
                        $this->despawnFromAll();
                }

                $this->setLevel($targetLevel);
                $this->level->addEntity($this);
                $this->chunk = null;

                return true;
        }

        public function getId() : int
        {
                return $this->id;
        }

        /**
         * @return Player[]
         */
        public function getViewers() : array
        {
                return $this->hasSpawned;
        }

        /**
         * Called by spawnTo() to send whatever packets needed to spawn the entity to the client.
         */
        protected function sendSpawnPacket(Player $player) : void
        {
                $links = [];
                if (count($this->passengers) !== 0) {
                        foreach ($this->getPassengers() as $passenger) {
                                $passenger->spawnTo($player);
                        }

                        $links = array_map(function (int $entityId) {
                                return new EntityLink($this->getId(), $entityId, EntityLink::TYPE_RIDER, true, false);
                        }, $this->passengers);
                }

                $player->sendDataPacket(AddActorPacket::create(
                        $this->getId(),
                        $this->getId(),
                        static::NETWORK_ID,
                        $this->asVector3(),
                        $this->getMotion(),
                        $this->pitch,
                        $this->yaw,
                        $this->headYaw ?? $this->yaw,
                        $this->yaw,
                        $this->attributeMap->getAll(),
                        $this->propertyManager->getAll(),
                        new PropertySyncData([], []),
                        $links
                ));
        }

        public function spawnTo(Player $player) : void
        {
                $id = spl_object_id($player);
                //TODO: this will cause some visible lag during chunk resends; if the player uses a spawn egg in a chunk, the
                //created entity won't be visible until after the resend arrives. However, this is better than possibly crashing
                //the player by sending them entities too early.
                if (
                        !isset($this->hasSpawned[$id]) &&
                        $this->chunk !== null &&
                        isset($player->usedChunks[Level::chunkHash($this->chunk->getX(), $this->chunk->getZ())]) &&
                        $player->usedChunks[Level::chunkHash($this->chunk->getX(), $this->chunk->getZ())] === true
                ) {
                        $this->hasSpawned[$id] = $player;

                        $this->sendSpawnPacket($player);
                }
        }

        public function spawnToAll() : void
        {
                if ($this->chunk === null || $this->closed) {
                        return;
                }
                foreach ($this->getLevel()->getViewersForPosition($this) as $player) {
                        $this->spawnTo($player);
                }
        }

        public function respawnToAll() : void
        {
                foreach ($this->hasSpawned as $key => $player) {
                        unset($this->hasSpawned[$key]);
                        $this->spawnTo($player);
                }
        }

        /**
         * @deprecated WARNING: This function DOES NOT permanently hide the entity from the player. As soon as the entity or
         * player moves, the player will once again be able to see the entity.
         */
        public function despawnFrom(Player $player, bool $send = true) : void
        {
                $id = spl_object_id($player);
                if (isset($this->hasSpawned[$id])) {
                        if ($send) {
                                $pk = new RemoveActorPacket();
                                $pk->entityUniqueId = $this->id;
                                $player->dataPacket($pk);
                        }
                        unset($this->hasSpawned[$id]);
                }
        }

        /**
         * @deprecated WARNING: This function DOES NOT permanently hide the entity from viewers. As soon as the entity or
         * player moves, viewers will once again be able to see the entity.
         */
        public function despawnFromAll() : void
        {
                foreach ($this->hasSpawned as $player) {
                        $this->despawnFrom($player);
                }
        }

        /**
         * Returns the item that players will equip when middle-clicking on this entity.
         */
        public function getPickedItem() : ?Item
        {
                return null;
        }

        /**
         * Flags the entity to be removed from the world on the next tick.
         */
        public function flagForDespawn() : void
        {
                $this->needsDespawn = true;
                $this->scheduleUpdate();
        }

        public function isFlaggedForDespawn() : bool
        {
                return $this->needsDespawn;
        }

        /**
         * Returns whether the entity has been "closed".
         */
        public function isClosed() : bool
        {
                return $this->closed;
        }

        /**
         * Closes the entity and frees attached references.
         *
         * WARNING: Entities are unusable after this has been executed!
         */
        public function close() : void
        {
                if ($this->closeInFlight) {
                        return;
                }

                if (!$this->closed) {
                        $this->closeInFlight = true;
                        (new EntityDespawnEvent($this))->call();
                        $this->closed = true;

                        $this->despawnFromAll();
                        $this->hasSpawned = [];

                        if ($this->chunk !== null) {
                                $this->chunk->removeEntity($this);
                                $this->chunk = null;
                        }

                        if ($this->isValid()) {
                                $this->level->removeEntity($this);
                                $this->setLevel(null);
                        }

                        $this->namedtag = null;
                        $this->lastDamageCause = null;
                        $this->closeInFlight = false;
                }
        }

        public function setDataFlag(int $propertyId, int $flagId, bool $value = true, int $propertyType = self::DATA_TYPE_LONG) : void
        {
                if ($this->getDataFlag($propertyId, $flagId) !== $value) {
                        $flags = (int) $this->propertyManager->getPropertyValue($propertyId, $propertyType);
                        $flags ^= 1 << $flagId;
                        $this->propertyManager->setPropertyValue($propertyId, $propertyType, $flags);
                }
        }

        public function getDataFlag(int $propertyId, int $flagId) : bool
        {
                return (((int) $this->propertyManager->getPropertyValue($propertyId, -1)) & (1 << $flagId)) > 0;
        }

        /**
         * Wrapper around {@link Entity#getDataFlag} for generic data flag reading.
         */
        public function getGenericFlag(int $flagId) : bool
        {
                return $this->getDataFlag($flagId >= 64 ? self::DATA_FLAGS2 : self::DATA_FLAGS, $flagId % 64);
        }

        /**
         * Wrapper around {@link Entity#setDataFlag} for generic data flag setting.
         */
        public function setGenericFlag(int $flagId, bool $value = true) : void
        {
                $this->setDataFlag($flagId >= 64 ? self::DATA_FLAGS2 : self::DATA_FLAGS, $flagId % 64, $value, self::DATA_TYPE_LONG);
        }

        /**
         * @param Player[]|Player $player
         * @param mixed[][]       $data   Properly formatted entity data, defaults to everything
         *
         * @phpstan-param array<int, array{0: int, 1: mixed}> $data
         */
        public function sendData($player, ?array $data = null) : void
        {
                if (!is_array($player)) {
                        $player = [$player];
                }

                $pk = new SetActorDataPacket();
                $pk->entityRuntimeId = $this->getId();
                $pk->metadata = $data ?? $this->propertyManager->getAll();
                $pk->syncedProperties = new PropertySyncData([], []);

                foreach ($player as $p) {
                        if ($p === $this) {
                                continue;
                        }
                        $p->dataPacket(clone $pk);
                }

                if ($this instanceof Player) {
                        $this->dataPacket($pk);
                }
        }

        public function broadcastEntityEvent(int $eventId, ?int $eventData = null, ?array $targets = null) : void
        {
                $this->server->broadcastPacket(
                        $targets ?? $this->getViewers(),
                        ActorEventPacket::create($this->id, $eventId, $eventData ?? 0, null)
                );
        }

        public function broadcastAnimation(int $animationId, int $data, ?array $targets = null) : void{
                $this->server->broadcastPacket($targets ?? $this->getViewers(), AnimatePacket::create($this->id, $animationId, $data));
        }

        /**
         * Broadcasts a sound caused by the entity. If the entity is considered "silent", the sound will be dropped.
         * @param Player[]|null $targets
         */
        public function broadcastSound(Sound $sound, ?array $targets = null) : void
        {
                if (!$this->isSilent()) {
                        $this->level->addSound($sound, $targets ?? $this->getViewers());
                }
        }

        /**
         * @return Entity[]
         */
        public function getPassengers() : array
        {
                $passengers = [];

                foreach ($this->passengers as $id) {
                        $entity = $this->server->findEntity($id);
                        if ($entity !== null) {
                                $passengers[] = $entity;
                        }
                }

                return $passengers;
        }

        public function __destruct()
        {
                $this->close();
        }

        /**
         * Called when interacted or tapped by a Player
         */
        public function onFirstInteract(Player $player, Vector3 $clickPos) : bool
        {
                return false;
        }

        public function setClientPositionAndRotation(Vector3 $pos, float $yaw, float $pitch, int $clientMoveTicks, bool $immediate) : void
        {
                $this->clientPos = $pos;
                $this->clientYaw = $yaw;
                $this->clientPitch = $pitch;
                $this->clientMoveTicks = $clientMoveTicks;
        }

        public function setClientMotion(Vector3 $motion) : void
        {
                $this->motion = $motion;
        }

        public function __toString()
        {
                return (new ReflectionClass($this))->getShortName() . "(" . $this->getId() . ")";
        }

        /**
         * TODO: remove this BC hack in 4.0
         *
         * @param string $name
         *
         * @return mixed
         * @throws ErrorException
         */
        public function __get($name)
        {
                if ($name === "fireTicks") {
                        return $this->fireTicks;
                }
                throw new ErrorException("Undefined property: " . get_class($this) . "::\$" . $name);
        }

        /**
         * TODO: remove this BC hack in 4.0
         *
         * @param string $name
         * @param mixed  $value
         *
         * @throws ErrorException
         * @throws InvalidArgumentException
         */
        public function __set($name, $value)
        {
                if ($name === "fireTicks") {
                        $this->setFireTicks($value);
                } else {
                        throw new ErrorException("Undefined property: " . get_class($this) . "::\$" . $name);
                }
        }

        /**
         * TODO: remove this BC hack in 4.0
         *
         * @param string $name
         *
         * @return bool
         */
        public function __isset($name)
        {
                return $name === "fireTicks";
        }
}
