<?php

namespace Eoko\Kes;

interface IdentifiableInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     */
    public function setId(string $id): void;
}
