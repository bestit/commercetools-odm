<?php

namespace BestIt\CommercetoolsODM;

use Doctrine\Common\EventManager;

/**
 * Provides unit of works for this odm package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWorkFactory implements UnitOfWorkFactoryInterface
{
    /**
     * The default event manager.
     * @var EventManager
     */
    private $eventManager = null;

    /**
     * UnitOfWorkFactory constructor.
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->setEventManager($eventManager);
    }

    /**
     * Returns the default event manager.
     * @return EventManager
     */
    protected function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * Returns a fresh instance of the unit of work for the given document manager.
     * @param DocumentManagerInterface $documentManager
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(DocumentManagerInterface $documentManager): UnitOfWorkInterface
    {
        return new UnitOfWork($documentManager, $this->getEventManager());
    }

    /**
     * Sets the default event manager.
     * @param EventManager $eventManager
     * @return UnitOfWorkFactory
     */
    protected function setEventManager(EventManager $eventManager): UnitOfWorkFactory
    {
        $this->eventManager = $eventManager;

        return $this;
    }
}
