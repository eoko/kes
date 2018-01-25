<?php

namespace Eoko\Tests\Kes;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Eoko\Kes\BaseEntityInterface;
use Eoko\Kes\EntityCacheManager;
use Eoko\Kes\IdentifiableTrait;
use Eoko\Kes\Plugins\DisabledPlugin\DisabledPlugin;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityInterface;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityTrait;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataEntityInterface;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataEntityTrait;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataPlugin;
use Eoko\Kes\Plugins\SkuPlugin\SkuEntityTrait;
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
    use IdentifiableTrait, SkuEntityTrait, MetadataEntityTrait, DisableEntityTrait;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $custom;

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
     * @return string
     */
    public function internalName(): string
    {
        return 'sample';
    }
}
