<?php

namespace Eoko\Kes\Plugins\DisabledPlugin;

interface DisableEntityInterface
{
    /**
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * @param bool $disable
     *
     * @internal param bool $enabled
     */
    public function setDisabled(bool $disable): void;
}
