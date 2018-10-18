<?php

namespace Eoko\Kes\Plugins\SkuPlugin;

interface SkuEntityInterface
{
    /**
     * @param string $sku
     */
    public function setSku(string $sku): void;
}
