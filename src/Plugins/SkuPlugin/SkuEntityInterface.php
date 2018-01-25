<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

interface SkuEntityInterface
{
    /**
     * @param string $sku
     */
    public function setSku(string $sku): void;
}
