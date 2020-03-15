<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * The basic class to handle responses in the unit of work.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
abstract class ResponseHandlerAbstract implements ResponseHandlerInterface
{
    use DocumentManagerAwareTrait;
    use LoggerAwareTrait;

    /**
     * The unit of work of the document manager.
     *
     * @var UnitOfWorkInterface
     */
    private $unitOfWork;

    /**
     * ResponseHandlerAbstract constructor.
     *
     * @param DocumentManagerInterface $documentManager
     */
    public function __construct(DocumentManagerInterface $documentManager)
    {
        $this->setDocumentManager($documentManager);

        $this->logger = new NullLogger();
    }

    /**
     * Can this object handle the given response?
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool
    {
        return false;
    }

    /**
     * Returns the injected unit of work.
     *
     * @return UnitOfWorkInterface
     */
    protected function getUnitOfWork(): UnitOfWorkInterface
    {
        if (!$this->unitOfWork) {
            $this->unitOfWork = $this->getDocumentManager()->getUnitOfWork();
        }

        return $this->unitOfWork;
    }
}
