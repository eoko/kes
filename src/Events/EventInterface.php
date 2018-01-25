<?php

namespace Eoko\Kes\Events;

use Eoko\Kes\BaseEntityInterface;

interface EventInterface
{
    /**
     * GetOneEvent constructor.
     *
     * @param BaseEntityInterface $entity
     * @param array               $options
     */
    public function __construct(BaseEntityInterface $entity, $options = []);

    /**
     * @return BaseEntityInterface
     */
    public function getEntity(): BaseEntityInterface;

    /**
     * @return array
     */
    public function getOptions(): array;
}
