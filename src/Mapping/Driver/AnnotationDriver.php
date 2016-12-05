<?php

namespace BestIt\CommercetoolsODM\Mapping\Driver;

use BestIt\CommercetoolsODM\Mapping\Annotations\Annotation;
use BestIt\CommercetoolsODM\Mapping\Annotations\DraftClass;
use BestIt\CommercetoolsODM\Mapping\Annotations\Entity;
use BestIt\CommercetoolsODM\Mapping\Annotations\HasLifecycleCallbacks;
use BestIt\CommercetoolsODM\Mapping\Annotations\Id;
use BestIt\CommercetoolsODM\Mapping\Annotations\Key;
use BestIt\CommercetoolsODM\Mapping\Annotations\PostLoad;
use BestIt\CommercetoolsODM\Mapping\Annotations\PostPersist;
use BestIt\CommercetoolsODM\Mapping\Annotations\PostRemove;
use BestIt\CommercetoolsODM\Mapping\Annotations\PostUpdate;
use BestIt\CommercetoolsODM\Mapping\Annotations\PreFlush;
use BestIt\CommercetoolsODM\Mapping\Annotations\PrePersist;
use BestIt\CommercetoolsODM\Mapping\Annotations\PreUpdate;
use BestIt\CommercetoolsODM\Mapping\Annotations\Repository;
use BestIt\CommercetoolsODM\Mapping\Annotations\Version;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface as SpecialClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as OrignalClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver as BasicDriver;
use Doctrine\Common\Persistence\Mapping\MappingException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;

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
     * Loads the class annotations for this metadata
     * @param SpecialClassMetadataInterface $metadata
     * @param ReflectionClass $classReflection
     * @return AnnotationDriver
     */
    private function loadClassAnnotations(
        SpecialClassMetadataInterface $metadata,
        ReflectionClass $classReflection
    ): AnnotationDriver {
        $reader = $this->getReader();
        $classAnntations = $reader->getClassAnnotations($classReflection);

        array_walk($classAnntations, function (Annotation $classAnnotation) use ($classReflection, $metadata, $reader) {
            if ($classAnnotation instanceof Entity) {
                if ($map = $classAnnotation->getRequestMap()) {
                    $metadata->setRequestClassMap($map);
                }
            }

            if ($classAnnotation instanceof HasLifecycleCallbacks) {
                $events = [
                    PostLoad::class,
                    PostPersist::class,
                    PostRemove::class,
                    PostUpdate::class,
                    PreFlush::class,
                    PrePersist::class,
                    PreUpdate::class
                ];

                $reflectionMethods = $classReflection->getMethods(ReflectionMethod::IS_PUBLIC);

                array_walk($reflectionMethods, function (ReflectionMethod $reflectionMethod) use (
                    $events,
                    $metadata,
                    $reader
                ) {
                    array_walk($events, function (string $eventAnnoClass) use (
                        $metadata,
                        $reader,
                        $reflectionMethod
                    ) {
                        if ($eventAnno = $reader->getMethodAnnotation($reflectionMethod, $eventAnnoClass)) {
                            $metadata->addLifecycleEvent(
                                lcfirst(basename($eventAnnoClass)),
                                $reflectionMethod->getName()
                            );
                        }
                    });
                });
            }

            if ($classAnnotation instanceof DraftClass) {
                $metadata->setDraft($classAnnotation->getDraft());
            }

            if ($classAnnotation instanceof Repository) {
                $metadata->setRepository($classAnnotation->getClass());
            }
        });

        return $this;
    }

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

        $this
            ->loadClassAnnotations($metadata, $classReflection)
            ->loadFieldMappings($metadata);
    }

    /**
     * Loads the mappings on the properties.
     * @param SpecialClassMetadataInterface $metadata
     * @return AnnotationDriver
     * @throws MappingException
     */
    private function loadFieldMappings(SpecialClassMetadataInterface $metadata):AnnotationDriver
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

        $metadata->setFieldMappings($fields);

        return $this;
    }
}
