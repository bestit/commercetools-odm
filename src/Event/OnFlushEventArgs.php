<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Event;

use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Doctrine\Common\EventArgs;

/**
 * Event which is emitted in the flush of the unit of work.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Event
 */
class OnFlushEventArgs extends EventArgs
{
    /**
     * The used unit of work.
     *
     * @var UnitOfWorkInterface
     */
    private $unitOfWork;

    /**
     * OnFlushEventArgs constructor.
     *
     * @param UnitOfWorkInterface $unitOfWork
     */
    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * Returns the used unit of work.
     *
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(): UnitOfWorkInterface
    {
        return $this->unitOfWork;
    }
}
