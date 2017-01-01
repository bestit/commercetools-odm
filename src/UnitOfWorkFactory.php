<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
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
    use ActionBuilderProcessorAwareTrait, EventManagerAwareTrait, ListenerInvokerAwareTrait;

    /**
     * UnitOfWorkFactory constructor.
     * @param ActionBuilderProcessorInterface $actionBuilderProcessor
     * @param EventManager $eventManager
     * @param ListenersInvoker $listenersInvoker
     */
    public function __construct(
        ActionBuilderProcessorInterface $actionBuilderProcessor,
        EventManager $eventManager,
        ListenersInvoker $listenersInvoker
    ) {
        $this
            ->setActionBuilderProcessor($actionBuilderProcessor)
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
        return new UnitOfWork(
            $this->getActionBuilderProcessor(),
            $documentManager,
            $this->getEventManager(),
            $this->getListenerInvoker()
        );
    }
}
