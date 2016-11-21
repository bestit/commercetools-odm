<?php

namespace BestIt\CommercetoolsODM\Mapping\Driver;

use BestIt\CommercetoolsODM\Mapping\Annotations\DraftClass;
use BestIt\CommercetoolsODM\Mapping\Annotations\Entity;
use BestIt\CommercetoolsODM\Mapping\Annotations\Id;
use BestIt\CommercetoolsODM\Mapping\Annotations\Key;
use BestIt\CommercetoolsODM\Mapping\Annotations\Repository;
use BestIt\CommercetoolsODM\Mapping\Annotations\Version;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface as SpecialClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as OrignalClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver as BasicDriver;
use Doctrine\Common\Persistence\Mapping\MappingException;
use InvalidArgumentException;

/**
 * Loads the annotations for a persisting class.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Driver
 * @version $id$
 */
class AnnotationDriver extends BasicDriver
{
    /**
     * Which annotation entities are used for this driver.
     * @var array
     */
    protected $entityAnnotationClasses = [Entity::class => true];

    /**
     * Loads the metadata for the specified class into the provided container.
     * @param string $className
     * @param OrignalClassMetadataInterface $metadata
     * @return void
     */
    public function loadMetadataForClass($className, OrignalClassMetadataInterface $metadata)
    {
        if (!($metadata instanceof SpecialClassMetadataInterface)) {
            throw new InvalidArgumentException('Wrong metadata instance given.');
        }

        $classReflection = $metadata->getReflectionClass();

        $metadata->setFieldMappings($this->loadFieldMappings($metadata));

        /** @var Entity $entityAnno */
        $entityAnno = $this->getReader()->getClassAnnotation($classReflection, Entity::class);

        if ($map = $entityAnno->getRequestMap()) {
            $metadata->setRequestClassMap($map);
        }

        /** @var DraftClass $draftClassAnno */
        if ($draftClassAnno = $this->getReader()->getClassAnnotation($classReflection, DraftClass::class)) {
            $metadata->setDraft($draftClassAnno->getDraft());
        }

        /** @var Repository $repoAnno */
        if ($repoAnno = $this->getReader()->getClassAnnotation($classReflection, Repository::class)) {
            $metadata->setRepository($repoAnno->getClass());
        }
    }

    /**
     * Loads the mappings on the properties.
     * @param ClassMetadataInterface $metadata
     * @return array
     * @throws MappingException
     */
    protected function loadFieldMappings(SpecialClassMetadataInterface $metadata):array
    {
        $reader = $this->getReader();
        $reflection = $metadata->getReflectionClass();
        $fields = [];

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            $propAnnotations = $reader->getPropertyAnnotations($property);
            $fields[$name] = [];

            foreach ($propAnnotations as $annotation) {
                if ($annotation instanceof Id) {
                    $metadata->setIdentifier($name);
                }

                if ($annotation instanceof Key) {
                    $metadata->setKey($name);
                }

                if ($annotation instanceof Version) {
                    $metadata->setVersion($name);
                }
            }
        }

        return $fields;
    }
}
