<?php

namespace Eoko\Kes;

trait BaseEntityTrait
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $id;

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
}
