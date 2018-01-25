<?php
/**
 * Created by PhpStorm.
 * User: merlin
 * Date: 25/01/18
 * Time: 18:43
 */

namespace Eoko\Kes;


trait IdentifiableTrait
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