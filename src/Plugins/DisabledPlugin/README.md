kes-disabled-plugin
-------------------

> Like a soft delete, delete entity but keep it in storage for further use.

## Usage

### Create an entity

```php
<?php

class Foo implements \Eoko\Kes\BaseEntityInterface, \Eoko\Kes\Plugins\DisabledPlugin\DisableEntityInterface {
    use \Eoko\Kes\Plugins\DisabledPlugin\DisableEntityTrait, \Eoko\Kes\BaseEntityTrait;
}
```

```php
<?php

use Eoko\Kes\EntityCacheManager;
use Eoko\Kes\Plugins\DisabledPlugin\DisabledPlugin;
use Eoko\Kes\Plugins\DisabledPlugin\DisableEntityInterface;

$entityManager = new EntityCacheManager();

$entityManager->registerPlugin(new DisabledPlugin());

class SampleEntity implements DisableEntityInterface {
    
    public function isDisabled(): bool {
 // TODO: Implement isDisabled() method.
}
public function setDisabled(bool $disable): void {
 // TODO: Implement setDisabled() method.

```