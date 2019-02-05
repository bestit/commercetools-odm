<?php

namespace BestIt\CommercetoolsODM\Model;

/**
 * The repository for product projections.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Model
 * @subpackage Model
 */
class ProductProjectionRepository extends DefaultRepository implements ByKeySearchRepositoryInterface
{
    use ByKeySearchRepositoryTrait;
}
