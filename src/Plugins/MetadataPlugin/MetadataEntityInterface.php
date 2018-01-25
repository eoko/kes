<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

use DateTime;

interface MetadataEntityInterface
{
    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void;

    /**
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void;
}
