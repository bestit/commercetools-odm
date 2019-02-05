<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataFactory;

/**
 * Provides basic api to set and get the metadata factory.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
trait MetadataFactoryAwareTrait
{
    /**
     * @var ClassMetadataFactory The metadata factory.
     */
    protected $metadataFactory = null;

    /**
     * Gets the metadata factory used to gather the metadata of classes.
     *
     * This getter exists to enforce types.
     *
     * @return ClassMetadataFactory
     */
    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->metadataFactory;
    }

    /**
     * Sets the metadata factory used to gather the metadata of classes.
     *
     * @param ClassMetadataFactory $metadataFactory The used factory.
     *
     * @return $this
     */
    public function setMetadataFactory(ClassMetadataFactory $metadataFactory): self
    {
        $this->metadataFactory = $metadataFactory;
        
        return $this;
    }
}
