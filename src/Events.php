<?php

namespace BestIt\CommercetoolsODM;

/**
 * Event Storage for this odm.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @todo Rename Constants.
 * @version $id$
 */
final class Events
{
    const onConflict = 'onConflict';
    const onFlush = 'onFlush';

    /**
     * Event before the persisting in the unit of work.
     * @var string
     */
    const prePersist = 'prePersist';

    const preRemove = 'preRemove';
    const preUpdate = 'preUpdate';
    const postRemove = 'postRemove';
    const postUpdate = 'postUpdate';
    const postLoad = 'postLoad';

    /**
     * Events constructor.
     *
     * Disallow the constuction of this class.
     */
    private function __construct()
    {
    }
}
