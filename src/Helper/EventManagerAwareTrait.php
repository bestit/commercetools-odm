<?php

namespace BestIt\CommercetoolsODM\Helper;

use Doctrine\Common\EventManager;

/**
 * Helps handling the event manager.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
trait EventManagerAwareTrait
{
    /**
     * The event dispatcher.
     * @var EventManager
     */
    private $eventManager = null;

    /**
     * Returns the event manager,
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * Sets the event manager.
     * @param EventManager $eventManager
     * @return $this
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }
}
