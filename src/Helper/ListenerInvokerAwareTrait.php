<?php

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\Event\ListenersInvoker;

/**
 * Helps with the listener invoker.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
trait ListenerInvokerAwareTrait
{
    /**
     * The listener invoker for events.
     * @var ListenersInvoker
     */
    private $listenerInvoker = null;

    /**
     * Returns the invoker for the event listener.
     * @return ListenersInvoker
     */
    public function getListenerInvoker(): ListenersInvoker
    {
        return $this->listenerInvoker;
    }

    /**
     * Sets the invoker for the event listener.
     * @param ListenersInvoker $listenerInvoker
     * @return $this
     */
    public function setListenerInvoker(ListenersInvoker $listenerInvoker)
    {
        $this->listenerInvoker = $listenerInvoker;

        return $this;
    }
}
