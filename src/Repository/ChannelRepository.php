<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryTrait;
use BestIt\CommercetoolsODM\Model\DefaultRepository;

/**
 * The repository to fetch channels.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
class ChannelRepository extends DefaultRepository implements ByKeySearchRepositoryInterface
{
    use ByKeySearchRepositoryTrait;
}
