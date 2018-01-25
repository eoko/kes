<?php

namespace Eoko\Kes\Plugins\DisabledPlugin;

use Eoko\Kes\BaseEntityInterface;
use Exception;

class DisabledEntityException extends Exception
{
    public function __construct(BaseEntityInterface $entity)
    {
        parent::__construct('The entity `'.get_class($entity).'` with id `'.$entity->getId().'` is disabled.');
    }
}
