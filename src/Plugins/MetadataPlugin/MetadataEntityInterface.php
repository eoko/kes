<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

use DateTime;

interface MetadataEntityInterface
{
    /**
     * @param DateTime $date
     */
    public function setCreated(DateTime $date): void;

    /**
     * @param DateTime $date
     */
    public function setUpdated(DateTime $date): void;
}
