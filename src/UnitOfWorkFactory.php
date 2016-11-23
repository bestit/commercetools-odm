<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use Doctrine\Common\EventManager;

/**
 * Provides unit of works for this odm package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWorkFactory implements UnitOfWorkFactoryInterface
{
    use EventManagerAwareTrait, ListenerInvokerAwareTrait;

    /**
     * UnitOfWorkFactory constructor.
     * @param EventManager $eventManager
     * @param ListenersInvoker $listenersInvoker
     */
    public function __construct(EventManager $eventManager, ListenersInvoker $listenersInvoker)
    {
        $this
            ->setEventManager($eventManager)
            ->setListenerInvoker($listenersInvoker);
    }

    /**
     * Returns a fresh instance of the unit of work for the given document manager.
     * @param DocumentManagerInterface $documentManager
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(DocumentManagerInterface $documentManager): UnitOfWorkInterface
    {
        return new UnitOfWork($documentManager, $this->getEventManager(), $this->getListenerInvoker());
    }
}
