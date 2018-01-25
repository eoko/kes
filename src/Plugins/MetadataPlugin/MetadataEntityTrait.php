<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

use DateTime;

trait MetadataEntityTrait
{
    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    protected $created;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    protected $updated;

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }
}
