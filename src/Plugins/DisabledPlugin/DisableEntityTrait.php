<?php

namespace Eoko\Kes\Plugins\DisabledPlugin;

trait DisableEntityTrait
{
    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    protected $disabled = false;

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }
}
