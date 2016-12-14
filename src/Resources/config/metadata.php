<?php

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

return [
    Commercetools\Core\Model\Product\Product::class => [
        'draft' => \Commercetools\Core\Model\Product\ProductDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Products\ProductCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Products\ProductDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\Products\ProductDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\Products\ProductByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\Products\ProductByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Products\ProductQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Products\ProductUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\Products\ProductUpdateByKeyRequest::class),
    ],
    Commercetools\Core\Model\Product\ProductProjection::class => [
        'repository' => \BestIt\CommercetoolsODM\Model\ProductProjectionRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setFindById(\Commercetools\Core\Request\Products\ProductProjectionByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\Products\ProductProjectionByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Products\ProductProjectionQueryRequest::class)
    ],
    Commercetools\Core\Model\ProductType\ProductType::class => [
        'draft' => \Commercetools\Core\Model\ProductType\ProductTypeDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\ProductTypeRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\ProductTypes\ProductTypeCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\ProductTypes\ProductTypeDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\ProductTypes\ProductTypeByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\ProductTypes\ProductTypeUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeUpdateByKeyRequest::class),
    ]
];
