<?php

namespace Eoko\Tests\Kes;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Eoko\Kes\Adapters\SymfonyCacheTagAwareAdapter;
use Eoko\Kes\BaseEntityInterface;
use Eoko\Kes\EntityCacheManager;
use Eoko\Kes\BaseEntityTrait;
use Eoko\Kes\NameableInterface;
use Eoko\Kes\Plugins\DisabledPlugin\DisabledPlugin;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityInterface;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityTrait;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataEntityInterface;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataEntityTrait;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataPlugin;
use Eoko\Kes\Plugins\SkuPlugin\SkuEntityTrait;
use Eoko\Kes\Plugins\UniqueIdPlugin\UniqueIdPlugin;
use JMS\Serializer\Annotation as Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class SampleTest extends TestCase
{
    public function testSample()
    {
        AnnotationRegistry::registerLoader('class_exists');

        $adapter = new SymfonyCacheTagAwareAdapter(new TagAwareAdapter(new RedisAdapter(RedisAdapter::createConnection('redis://localhost'))));
        $manager = new EntityCacheManager($adapter);

        $manager->registerPlugin(new MetadataPlugin());
        $manager->registerPlugin(new UniqueIdPlugin());
        $manager->registerPlugin(new DisabledPlugin());

        $entity = new SampleEntity();
        $entity->setId(1);
        $entity->setCustom('hello');

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

class SampleEntity implements BaseEntityInterface, MetadataEntityInterface, DisableEntityInterface, NameableInterface
{
    use BaseEntityTrait, SkuEntityTrait, MetadataEntityTrait, DisableEntityTrait;

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

    public function name(): string
    {
        return 'sample';
    }
}
