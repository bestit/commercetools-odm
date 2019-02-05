<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Mapping;

use BestIt\CommercetoolsODM\Mapping\ClassMetadata as ClassMetadataImplemented;
use Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\ReflectionService;
use InvalidArgumentException;

/**
 * The factory for the metadata.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping
 */
class ClassMetadataFactory extends AbstractClassMetadataFactory
{
    /**
     * Caches symfony bundle names and their matching namespaces.
     *
     * @todo Document or Entity? Rename it.
     * @var array
     */
    protected $documentNamespaces = [];

    /**
     * The used mapping driver.
     *
     * @var MappingDriver
     */
    protected $driver = null;

    /**
     * Refactor everything with this!
     *
     * @var array
     */
    protected $sourceMetadata = [];

    /**
     * ClassMetadataFactory constructor.
     *
     * @param MappingDriver $driver
     */
    public function __construct(MappingDriver $driver)
    {
        $this->setDriver($driver);

        $this->sourceMetadata = require __DIR__ . DIRECTORY_SEPARATOR . '/../Resources/config/metadata.php';
    }

    /**
     * Actually loads the metadata from the underlying metadata.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param ClassMetadata $class
     * @param ClassMetadata|null $parent
     * @param bool $rootEntityFound
     * @param array $nonSuperclassParents All parent class names that are not marked as mapped superclasses.
     *
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
     * Returns the bundle namespaces.
     *
     * @return array
     */
    public function getBundleNamespaces(): array
    {
        return $this->documentNamespaces;
    }

    /**
     * Returns the mapping driver implementation.
     *
     * @return MappingDriver
     */
    protected function getDriver(): MappingDriver
    {
        return $this->driver;
    }

    /**
     * Gets the fully qualified class-name from the namespace alias.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @throws BadMethodCallException This is not yet supported.
     *
     * @param string $namespaceAlias
     * @param string $simpleClassName
     *
     * @return string
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
     *
     * @return void
     */
    protected function initialize()
    {
        $this->initialized = true;
    }

    /**
     * Initializes Reflection after ClassMetadata was constructed.
     *
     * @param ClassMetadata $class
     * @param ReflectionService $reflService
     *
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
     *
     * @param ClassMetadata $class
     *
     * @return boolean
     */
    protected function isEntity(ClassMetadata $class): bool
    {
        return !$this->getDriver()->isTransient($class->getName());
    }

    /**
     * Creates a new ClassMetadata instance for the given class name.
     *
     * @param string $className
     *
     * @phpcsSuppress BestIt.TypeHints.ReturnTypeDeclaration.MissingReturnTypeHint
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return ClassMetadata
     */
    protected function newClassMetadataInstance($className)
    {
        return new ClassMetadataImplemented($className);
    }

    /**
     * Sets the bundle namespaces.
     *
     * @param array $documentNamespaces
     *
     * @return ClassMetadataFactory
     */
    public function setBundleNamespaces(array $documentNamespaces): ClassMetadataFactory
    {
        $this->documentNamespaces = $documentNamespaces;
        return $this;
    }

    /**
     * Sets the mapping driver.
     *
     * @param MappingDriver $driver
     *
     * @return ClassMetadataFactory
     */
    protected function setDriver(MappingDriver $driver): ClassMetadataFactory
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Wakes up reflection after ClassMetadata gets unserialized from cache.
     *
     * @param ClassMetadata $class
     * @param ReflectionService $reflService
     *
     * @return void
     */
    protected function wakeupReflection(ClassMetadata $class, ReflectionService $reflService)
    {
        // Do Nothing. Just fulfill api.
    }
}
