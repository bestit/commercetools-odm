<?php

namespace BestIt\CommercetoolsODM\Event;

use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager;

/**
 * A method invoker based on entity lifecycle.
 *
 * @author  Fabio B. Silva <fabio.bat.silva@gmail.com>
 * @since   2.4
 */
class ListenersInvoker
{
    use EventManagerAwareTrait;

    /**
     * External check if nothing should be invoked.
     * @var int
     */
    const INVOKE_NONE = 0;

    /**
     * Invoke just the entity listeners.
     * @var int
     */
    const INVOKE_LISTENERS = 1;

    /**
     * Invoke the lifecycle callbacks.
     * @var int
     */
    const INVOKE_CALLBACKS = 2;

    /**
     * Invoke the listeners on the event manager.
     * @var int
     */
    const INVOKE_MANAGER = 4;

    /**
     * ListenersInvoker constructor.
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->setEventManager($eventManager);
    }

    /**
     * Which event systems are subscribed to the given event?
     * @param ClassMetadataInterface $metadata
     * @param string $eventName
     * @return int Returns the bitmask of the invoked callbacks.
     */
    public function getSubscribedSystems(ClassMetadataInterface $metadata, string $eventName): int
    {
        $invoke = self::INVOKE_NONE;

        if ($metadata->hasLifecycleEvents($eventName)) {
            $invoke |= self::INVOKE_CALLBACKS;
        }

        if (isset($metadata->entityListeners[$eventName])) {
            $invoke |= self::INVOKE_LISTENERS;
        }

        if ($this->eventManager->hasListeners($eventName)) {
            $invoke |= self::INVOKE_MANAGER;
        }

        return $invoke;
    }

    /**
     * Call the registered callbacks for the given event name.
     * @param EventArgs $args
     * @param string $eventName
     * @param mixed $entity
     * @param ClassMetadataInterface $metadata
     * @param int|void $invoke
     */
    public function invoke(
        EventArgs $args,
        string $eventName,
        $entity,
        ClassMetadataInterface $metadata,
        int $invoke = null
    ) {
        if ($invoke === null) {
            $invoke = $this->getSubscribedSystems($metadata, $eventName);
        }

        if ($invoke & self::INVOKE_CALLBACKS) {
            $callbacks = $metadata->getLifecycleEvents($eventName);

            array_walk($callbacks, function ($callback) use ($args, $entity) {
                $entity->$callback($args);
            });
        }

        if ($invoke & self::INVOKE_LISTENERS) {
            array_walk($metadata->entityListeners[$eventName], function ($listener) use ($args, $entity) {
                $class = $listener['class'];
                $method = $listener['method'];

                (new $class)->$method($entity, $args);
            });
        }

        if ($invoke & self::INVOKE_MANAGER) {
            $this->getEventManager()->dispatchEvent($eventName, $args);
        }
    }
}
