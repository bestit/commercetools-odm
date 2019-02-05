<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Model\Project\Project;

/**
 * Class ProjectRepository
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 * @subpackage Repository
 */
interface ProjectRepositoryInterface
{
    /**
     * Returns the info for the actual projcet.
     *
     * @throws ResponseException
     *
     * @return Project
     */
    public function getInfoForActualProject(): Project;
}
