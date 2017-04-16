<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryTrait;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\ProductType\ProductType;

/**
 * The repo for the product types.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class ProductTypeRepository extends DefaultRepository implements ByKeySearchRepositoryInterface
{
    use ByKeySearchRepositoryTrait;

    /**
     * Saves the given product tpe.
     * @param ProductType $type
     * @return ProductType
     */
    public function save(ProductType $type): ProductType
    {
        $this->getDocumentManager()->persist($type);

        $this->getDocumentManager()->flush();

        return $type;
    }
}
