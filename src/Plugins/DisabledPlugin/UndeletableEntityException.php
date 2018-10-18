<?php

namespace Eoko\Kes\Plugins\DisabledPlugin;

use Eoko\Kes\BaseEntityInterface;
use Exception;

class UndeletableEntityException extends Exception
{
    public function __construct(BaseEntityInterface $entity)
    {
        parent::__construct('The entity of type `'.get_class($entity).'` can only be disabled.');
    }
}
