<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM;

/**
 * Event Storage for this odm.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
final class Events
{
    /**
     * Conflict event.
     * @var string
     */
    const ON_CONFLICT = 'onConflict';

    /**
     * Event for the flush event.
     * @var string
     */
    const ON_FLUSH = 'onFlush';

    /**
     * Event before the persisting in the unit of work.
     * @var string
     */
    const PRE_PERSIST = 'prePersist';

    /**
     * Event for the preparation of the remove.
     * ar string
     */
    const PRE_REMOVE = 'preRemove';

    /**
     * Event for the update preparation.
     * @var string
     */
    const PRE_UPDATE = 'preUpdate';

    /**
     * Event after the detach.
     * @var string
     */
    const POST_DETACH = 'postDetach';

    /**
     * @var string Event after registering a document in the unit of work.
     */
    const POST_REGISTER = 'postRegister';

    /**
     * Event after the removal.
     * @var string
     */
    const POST_REMOVE = 'postRemove';

    /**
     * Event after the update.
     * @var string
     */
    const POST_PERSIST = 'postPersist';

    /**
     * Event after the load.
     * @var string
     */
    const POST_LOAD = 'postLoad';

    /**
     * Events constructor.
     *
     * Disallow the construction of this class.
     */
    private function __construct()
    {
    }
}
