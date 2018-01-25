<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

trait SkuEntityTrait
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $sku;

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }
}
