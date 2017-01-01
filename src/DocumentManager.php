<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Request\AbstractApiRequest;
use Commercetools\Core\Request\AbstractCreateRequest;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Request\AbstractUpdateRequest;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectRepository;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Basic manager for the commercetools processes.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @todo Add api for multiple clients.
 * @version $id$
 */
class DocumentManager implements DocumentManagerInterface
{
    use ClientAwareTrait, MetadataFactoryAwareTrait, QueryHelperAwareTrait;

    /**
     * The repository factory.
     * @var RepositoryFactoryInterface
     */
    private $repositoryFactory = null;

    /**
     * The unit of work for this manager.
     * @var UnitOfWorkInterface
     */
    private $unitOfWork = null;

    /**
     * DocumentManager constructor.
     * @param ClassMetadataFactory $metadataFactory
     * @param Client $client
     * @param QueryHelper $queryHelper
     * @param RepositoryFactoryInterface $repositoryFactory
     * @param UnitOfWorkFactoryInterface $unitOfWorkFactory
     */
    public function __construct(
        ClassMetadataFactory $metadataFactory,
        Client $client,
        QueryHelper $queryHelper,
        RepositoryFactoryInterface $repositoryFactory,
        UnitOfWorkFactoryInterface $unitOfWorkFactory
    ) {
        $this
            ->setClient($client)
            ->setMetadataFactory($metadataFactory)
            ->setQueryHelper($queryHelper)
            ->setRepositoryFactory($repositoryFactory)
            ->setUnitOfWork($unitOfWorkFactory->getUnitOfWork($this));
    }

    /**
     * Returns a request class for fetching/updating/deleting documents using the request map of the given class name.
     * @param string $className
     * @param string $requestType
     * @param array $args The optional arguments.
     * @return AbstractCreateRequest|AbstractUpdateRequest|AbstractDeleteRequest|AbstractApiRequest
     * @throws InvalidArgumentException
     */
    public function createRequest(string $className, $requestType = self::REQUEST_TYPE_QUERY, ...$args)
    {
        $metadata = $this->getClassMetadata($className);

        if (!($metadata instanceof ClassMetadataInterface)) {
            throw new InvalidArgumentException('The given metadata class was of the wrong type.');
        }

        $map = $metadata->getRequestClassMap();

        if (!$map) {
            throw new InvalidArgumentException(sprintf(
                'There is no request map for %s / %s.',
                $className,
                $requestType
            ));
        }

        $requestReflection = new ReflectionClass($map->{'get' . $requestType}());

        return $requestReflection->newInstanceArgs($args);
    }

    /**
     * Detaches an object from the ObjectManager, causing a managed object to
     * become detached. Unflushed changes made to the object if any
     * (including removal of the object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     *
     * @param object $object The object to detach.
     *
     * @return void
     */
    public function detach($object)
    {
        $this->getUnitOfWork()->detach($object);
    }

    /**
     * Returns the ClassMetadata descriptor for a class.
     *
     * The class name must be the fully-qualified class name without a leading backslash
     * (as it is returned by get_class($obj)).
     * @param string $className
     * @return ClassMetadata
     * @todo Create a setter and lazy loading for this.
     */
    public function getClassMetadata($className): ClassMetadata
    {
        return $this->getMetadataFactory()->getMetadataFor($className);
    }

    /**
     * Gets the repository for a class.
     * @param string $className
     * @return ObjectRepository
     */
    public function getRepository($className): ObjectRepository
    {
        return $this->getRepositoryFactory()->getRepository($this, $className);
    }

    /**
     * Returns the repository factory.
     * @return RepositoryFactoryInterface
     */
    protected function getRepositoryFactory(): RepositoryFactoryInterface
    {
        return $this->repositoryFactory;
    }

    /**
     * Returns the unit of work for this manager.
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(): UnitOfWorkInterface
    {
        return $this->unitOfWork;
    }

    /**
     * Finds an object by its identifier.
     *
     * This is just a convenient shortcut for getRepository($className)->find($id).
     *
     * @param string $className The class name of the object to find.
     * @param mixed $id The identity of the object to find.
     *
     * @return object The found object.
     */
    public function find($className, $id)
    {
        return $this->getRepositoryFactory()->getRepository($this, $className)->find($id);
    }

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     * @param object $object The instance to make managed and persistent.
     * @return void
     */
    public function persist($object)
    {
        $this->getUnitOfWork()->scheduleSave($object);
    }

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the flush operation.
     *
     * @param object $object The object instance to remove.
     *
     * @return void
     */
    public function remove($object)
    {
    }

    /**
     * Sets the repository factory.
     * @param RepositoryFactoryInterface $repositoryFactory
     * @return DocumentManager
     */
    protected function setRepositoryFactory(RepositoryFactoryInterface $repositoryFactory): DocumentManager
    {
        $this->repositoryFactory = $repositoryFactory;
        return $this;
    }

    /**
     * Sets the unit of work for this manager.
     * @param UnitOfWorkInterface $unitOfWork
     * @return DocumentManager
     */
    protected function setUnitOfWork(UnitOfWorkInterface $unitOfWork): DocumentManager
    {
        $this->unitOfWork = $unitOfWork;

        return $this;
    }

    /**
     * Merges the state of a detached object into the persistence context
     * of this ObjectManager and returns the managed copy of the object.
     * The object passed to merge will not become associated/managed with this ObjectManager.
     *
     * @param object $object
     *
     * @return object
     */
    public function merge($object)
    {
    }

    /**
     * Clears the ObjectManager. All objects that are currently managed
     * by this ObjectManager become detached.
     *
     * @param string|null $objectName if given, only objects of this type will get detached.
     *
     * @return void
     */
    public function clear($objectName = null)
    {
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $object The object to refresh.
     *
     * @return void
     */
    public function refresh($object)
    {
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     * @return void
     */
    public function flush()
    {
        $this->unitOfWork->flush();
    }

    /**
     * Helper method to initialize a lazy loading proxy or persistent collection.
     *
     * This method is a no-op for other objects.
     *
     * @param object $obj
     *
     * @return void
     */
    public function initializeObject($obj)
    {
    }

    /**
     * Checks if the object is part of the current UnitOfWork and therefore managed.
     *
     * @param object $object
     *
     * @return bool
     */
    public function contains($object)
    {
    }
}
