<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataFactory;

/**
 * Provides basic api to set and get the metadata factory.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
trait MetadataFactoryAwareTrait
{
    /**
     * The metadata factory.
     * @var ClassMetadataFactory
     */
    private $metadataFactory = null;

    /**
     * Gets the metadata factory used to gather the metadata of classes.
     * @return ClassMetadataFactory
     */
    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    /**
     * Sets the metadata factory used to gather the metadata of classes.
     * @param ClassMetadataFactory $metadataFactory
     * @return $this
     */
    public function setMetadataFactory(ClassMetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
        return $this;
    }
}
