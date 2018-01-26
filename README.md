KES
===
[![Build Status](https://travis-ci.org/eoko/kes.svg?branch=master)](https://travis-ci.org/eoko/kes)

Store entity in a key/value database like Redis.

## Usage

### Create Manager

```php
<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Eoko\Kes\EntityCacheManager;
use Eoko\Kes\Plugins\DisabledPlugin\DisabledPlugin;
use Eoko\Kes\Plugins\MetadataPlugin\MetadataPlugin;
use Eoko\Kes\Plugins\UniqueIdPlugin\UniqueIdPlugin;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;

AnnotationRegistry::registerLoader('class_exists');

$eventDispatcher = new EventDispatcher();
$serializer = SerializerBuilder::create()->build();
$adapter = new TagAwareAdapter(new ArrayAdapter());

$manager = new EntityCacheManager($adapter);

// Register some useful plugins
$manager->registerPlugin(new MetadataPlugin());
$manager->registerPlugin(new UniqueIdPlugin());
$manager->registerPlugin(new DisabledPlugin());
```


```php

```

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
```