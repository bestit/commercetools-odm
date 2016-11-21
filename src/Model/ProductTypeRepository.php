<?php

namespace BestIt\CommercetoolsODM\Model;

/**
 * the repository for product types.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Model
 * @version $id$
 */
class ProductTypeRepository extends DefaultRepository implements ByKeySearchRepositoryInterface
{
    use ByKeySearchRepositoryTrait;
}
