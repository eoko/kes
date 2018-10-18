<?php

namespace Eoko\Kes\Events;

use Eoko\Kes\BaseEntityInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractEvent extends Event implements EventInterface
{
    /** @var BaseEntityInterface */
    protected $entity;

    /** @var array */
    protected $options;

    /**
     * GetOneEvent constructor.
     *
     * @param BaseEntityInterface $entity
     * @param array               $options
     */
    public function __construct(BaseEntityInterface $entity, $options = [])
    {
        $this->entity = $entity;
        $this->options = $options;
    }

    /**
     * @return BaseEntityInterface
     */
    public function getEntity(): BaseEntityInterface
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
