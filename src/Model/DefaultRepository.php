<?php

namespace BestIt\CommercetoolsODM\Model;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\ClientRequestInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use UnexpectedValueException;

/**
 * The default repository for this commercetools package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage $id$
 * @version $id$
 */
class DefaultRepository implements ObjectRepository
{
    use DocumentManagerAwareTrait;

    /**
     * The metadata for the used class.
     * @var ClassMetadataInterface
     */
    private $metdata = null;

    /**
     * DefaultRepository constructor.
     * @param ClassMetadataInterface $metadata
     * @param DocumentManagerInterface $documentManager
     */
    public function __construct(ClassMetadataInterface $metadata, DocumentManagerInterface $documentManager)
    {
        $this
            ->setDocumentManager($documentManager)
            ->setMetdata($metadata);
    }

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @return object The object.
     */
    public function find($id)
    {
    }

    /**
     * Finds all objects in the repository.
     * @return array The objects.
     */
    public function findAll(): array
    {
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array The objects.
     * @throws UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
    }

    /**
     * Finds a single object by a set of criteria.
     * @param array $criteria The criteria.
     * @return mixed The object.
     */
    public function findOneBy(array $criteria)
    {
    }

    /**
     * Returns the class name of the object managed by the repository.
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getMetdata()->getName();
    }

    /**
     * Returns the metadata for the used class.
     * @return ClassMetadataInterface
     */
    protected function getMetdata(): ClassMetadataInterface
    {
        return $this->metdata;
    }

    /**
     * Processes the given query.
     * @param ClientRequestInterface $request
     * @return array<mixed|ApiResponseInterface|ClientRequestInterface> The mapped response, the raw response, the
     *         request.
     */
    protected function processQuery(ClientRequestInterface $request): array
    {
        $response = $this->getDocumentManager()->getClient()->execute($request);

        return [$request->mapResponse($response), $response, $request];
    }

    /**
     * Sets the metadata for the used class.
     * @param ClassMetadataInterface $metdata
     * @return DefaultRepository
     */
    protected function setMetdata(ClassMetadataInterface $metdata): DefaultRepository
    {
        $this->metdata = $metdata;

        return $this;
    }
}
