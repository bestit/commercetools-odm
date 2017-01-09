<?php

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

return [
    \Commercetools\Core\Model\Category\Category::class => [
        'draft' => \Commercetools\Core\Model\Category\CategoryDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Categories\CategoryCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Categories\CategoryDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Categories\CategoryByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Categories\CategoryQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Categories\CategoryUpdateRequest::class)
    ],

    Commercetools\Core\Model\CustomObject\CustomObject::class => [
        'draft' => \Commercetools\Core\Model\CustomObject\CustomObjectDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\CustomObjectRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest::class)
            ->setDeleteByContainerAndKey(\Commercetools\Core\Request\CustomObjects\CustomObjectByKeyGetRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\CustomObjects\CustomObjectDeleteRequest::class)
            ->setFindByContainerAndKey(\Commercetools\Core\Request\CustomObjects\CustomObjectByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\CustomObjects\CustomObjectQueryRequest::class)
    ],

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
