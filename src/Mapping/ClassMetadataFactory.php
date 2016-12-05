<?php

namespace BestIt\CommercetoolsODM\Mapping;

use BestIt\CommercetoolsODM\Mapping\ClassMetadata as ClassMetadataImplemented;
use Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\ReflectionService;
use InvalidArgumentException;

class ClassMetadataFactory extends AbstractClassMetadataFactory
{
    /**
     * Refactor everything with this!
     * @var array
     */
    protected $sourceMetadata = [];

    /**
     * Caches symfony bundle names and their matching namespaces.
     * @var array
     * @todo Document or Entity? Rename it.
     */
    protected $documentNamespaces = [];

    /**
     * The used mapping driver.
     * @var MappingDriver
     */
    protected $driver = null;

    /**
     * ClassMetadataFactory constructor.
     * @param MappingDriver $driver
     */
    public function __construct(MappingDriver $driver)
    {
        $this->setDriver($driver);

        $this->sourceMetadata = require __DIR__ . DIRECTORY_SEPARATOR . '/../Resources/config/metadata.php';
    }

    /**
     * Returns the bundle namespaces.
     * @return array
     */
    public function getBundleNamespaces(): array
    {
        return $this->documentNamespaces;
    }

    /**
     * Returns the mapping driver implementation.
     * @return MappingDriver
     */
    protected function getDriver(): MappingDriver
    {
        return $this->driver;
    }

    /**
     * Gets the fully qualified class-name from the namespace alias.
     * @param string $namespaceAlias
     * @param string $simpleClassName
     * @return string
     * @throws BadMethodCallException This is not yet supported.
     */
    protected function getFqcnFromAlias($namespaceAlias, $simpleClassName): string
    {
        $namespaces = $this->getBundleNamespaces();

        if (!array_key_exists($namespaceAlias, $namespaces)) {
            throw new InvalidArgumentException(sprintf('Namespace %s cannot be found.', $namespaceAlias));
        }

        return $namespaces[$namespaceAlias] . $simpleClassName;
    }

    /**
     * Lazy initialization of this stuff, especially the metadata driver,
     * since these are not needed at all when a metadata cache is active.
     * @return void
     */
    protected function initialize()
    {
        $this->initialized = true;
    }

    /**
     * Sets the bundle namespaces.
     * @param array $documentNamespaces
     * @return ClassMetadataFactory
     */
    public function setBundleNamespaces(array $documentNamespaces): ClassMetadataFactory
    {
        $this->documentNamespaces = $documentNamespaces;
        return $this;
    }

    /**
     * Sets the mapping driver.
     * @param MappingDriver $driver
     * @return ClassMetadataFactory
     */
    protected function setDriver(MappingDriver $driver): ClassMetadataFactory
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Wakes up reflection after ClassMetadata gets unserialized from cache.
     * @param ClassMetadata $class
     * @param ReflectionService $reflService
     * @return void
     */
    protected function wakeupReflection(ClassMetadata $class, ReflectionService $reflService)
    {
        // Do Nothing. Just fulfill api.
    }

    /**
     * Initializes Reflection after ClassMetadata was constructed.
     * @param ClassMetadata $class
     * @param ReflectionService $reflService
     * @return void
     */
    protected function initializeReflection(ClassMetadata $class, ReflectionService $reflService)
    {
        /** @var ClassMetadataImplemented $class */
        if (!($class instanceof ClassMetadataImplemented)) {
            throw new InvalidArgumentException('Wrong metadata instance given.');
        }

        $class->setReflectionClass($reflService->getClass($class->getName()));
    }

    /**
     * Checks whether the class metadata is an entity.
     *
     * This method should return false for mapped superclasses or embedded classes.
     * @param ClassMetadata $class
     * @return boolean
     */
    protected function isEntity(ClassMetadata $class)
    {
        return !$this->getDriver()->isTransient($class->getName());
    }

    /**
     * Actually loads the metadata from the underlying metadata.
     * @param ClassMetadata $class
     * @param ClassMetadata|null $parent
     * @param bool $rootEntityFound
     * @param array $nonSuperclassParents All parent class names
     *                                                 that are not marked as mapped superclasses.
     * @return void
     */
    protected function doLoadMetadata($class, $parent, $rootEntityFound, array $nonSuperclassParents)
    {
        /** @var ClassMetadataImplemented $class */
        if (!($class instanceof ClassMetadataImplemented)) {
            throw new InvalidArgumentException('Wrong metadata instance given.');
        }

        $this->getDriver()->loadMetadataForClass($className = $class->getName(), $class);

        if (array_key_exists($className, $this->sourceMetadata)) {
            foreach ($this->sourceMetadata[$className] as $key => $value) {
                $class->{'set' . ucfirst($key)}($value);
            }
        }
    }

    /**
     * Creates a new ClassMetadata instance for the given class name.
     * @param string $className
     * @return ClassMetadata
     */
    protected function newClassMetadataInstance($className)
    {
        return new ClassMetadataImplemented($className);
    }
}
