<?php

namespace Eoko\Tests\Kes;

use DateTime;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Eoko\Kes\BaseEntityInterface;
use Eoko\Kes\EntityCacheManager;
use Eoko\Kes\Plugins\DisabledPlugin\DisabledPlugin;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityInterface;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataEntityInterface;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataPlugin;
use Eoko\Kes\Plugins\UniqueIdPlugin\UniqueEntityInterface;
use Eoko\Kes\Plugins\UniqueIdPlugin\UniqueIdPlugin;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SampleTest extends TestCase
{
    public function testSample()
    {
        AnnotationRegistry::registerLoader('class_exists');

        $d = new EventDispatcher();
        $s = SerializerBuilder::create()->build();
        $a = new TagAwareAdapter(new ArrayAdapter());
        $manager = new EntityCacheManager($d, $a, $s);

        $manager->registerPlugin(new MetadataPlugin());
        $manager->registerPlugin(new UniqueIdPlugin());
        $manager->registerPlugin(new DisabledPlugin());

        $manager->registerEntity(new SampleEntity());

        $entity = new SampleEntity();

        $manager->createOneEntity($entity);

        /** @var MetadataEntityInterface|BaseEntityInterface|DisableEntityInterface $entity */
        $entity = $manager->getOneEntity($entity);
        $manager->updateOneEntity($entity);

        $entity->setDisabled(true);

        $manager->updateOneEntity($entity);

        $manager->getOneEntity($entity, ['ignoreDisabled' => true]);

        $entity->setDisabled(false);

        $manager->updateOneEntity($entity, ['ignoreDisabled' => false]);

//        $manager->deleteOneEntity($entity);
    }
}

class SampleEntity implements BaseEntityInterface, MetadataEntityInterface, UniqueEntityInterface, DisableEntityInterface
{
    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $disabled = false;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $id;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $custom;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    protected $created;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    protected $updated;

    /**
     * @return string
     */
    public function internalName(): string
    {
        return 'sample';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }
}
