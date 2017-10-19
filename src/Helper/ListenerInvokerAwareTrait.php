<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\Event\ListenersInvoker;

/**
 * Helps with the listener invoker.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Helper
 */
trait ListenerInvokerAwareTrait
{
    /**
     * @var ListenersInvoker|null The listener invoker for events.
     */
    protected $listenerInvoker;

    /**
     * Returns the invoker for the event listener.
     *
     * This getter exists to provide you a type safe way to work.
     * @return ListenersInvoker
     */
    public function getListenerInvoker(): ListenersInvoker
    {
        return $this->listenerInvoker;
    }

    /**
     * Sets the invoker for the event listener.
     * @param ListenersInvoker $listenerInvoker The listener invoker.
     * @return $this
     */
    public function setListenerInvoker(ListenersInvoker $listenerInvoker): self
    {
        $this->listenerInvoker = $listenerInvoker;

        return $this;
    }
}
