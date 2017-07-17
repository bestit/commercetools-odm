<?php

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

return [
    \Commercetools\Core\Model\Cart\Cart::class => [
        'draft' => \Commercetools\Core\Model\Cart\CartDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\CartRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Carts\CartCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Carts\CartDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Carts\CartByIdGetRequest::class)
            ->setFindByCustomerId(\Commercetools\Core\Request\Carts\CartByCustomerIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Carts\CartQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Carts\CartUpdateRequest::class)
    ],

    \Commercetools\Core\Model\Category\Category::class => [
        'draft' => \Commercetools\Core\Model\Category\CategoryDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Categories\CategoryCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Categories\CategoryDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Categories\CategoryByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Categories\CategoryQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Categories\CategoryUpdateRequest::class)
    ],

    \Commercetools\Core\Model\Channel\Channel::class => [
        'draft' => \Commercetools\Core\Model\Channel\ChannelDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\DefaultRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Channels\ChannelCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Channels\ChannelDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Channels\ChannelByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Channels\ChannelQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Channels\ChannelUpdateRequest::class)
    ],

    \Commercetools\Core\Model\CustomObject\CustomObject::class => [
        'draft' => \Commercetools\Core\Model\CustomObject\CustomObjectDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\CustomObjectRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest::class)
            ->setDeleteByContainerAndKey(\Commercetools\Core\Request\CustomObjects\CustomObjectByKeyGetRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\CustomObjects\CustomObjectDeleteRequest::class)
            ->setFindByContainerAndKey(\Commercetools\Core\Request\CustomObjects\CustomObjectByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\CustomObjects\CustomObjectQueryRequest::class)
    ],

    \Commercetools\Core\Model\Customer\Customer::class => [
        'draft' => \Commercetools\Core\Model\Customer\CustomerDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\CustomerRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Customers\CustomerCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Customers\CustomerDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Customers\CustomerByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Customers\CustomerQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Customers\CustomerUpdateRequest::class)
    ],

    \Commercetools\Core\Model\Order\Order::class => [
        'repository' => \BestIt\CommercetoolsODM\Repository\OrderRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setDeleteById(\Commercetools\Core\Request\Orders\OrderDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Orders\OrderByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Orders\OrderQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Orders\OrderUpdateRequest::class)
    ],

    \Commercetools\Core\Model\Product\Product::class => [
        'draft' => \Commercetools\Core\Model\Product\ProductDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\ProductRepository::class,
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

    \Commercetools\Core\Model\Product\ProductProjection::class => [
        'draft' => \Commercetools\Core\Model\Product\ProductDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\ProductProjectionRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setFindById(\Commercetools\Core\Request\Products\ProductProjectionByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\Products\ProductProjectionByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Products\ProductProjectionQueryRequest::class)
    ],

    \Commercetools\Core\Model\ProductType\ProductType::class => [
        'draft' => \Commercetools\Core\Model\ProductType\ProductTypeDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\ProductTypeRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\ProductTypes\ProductTypeCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\ProductTypes\ProductTypeDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\ProductTypes\ProductTypeByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\ProductTypes\ProductTypeUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\ProductTypes\ProductTypeUpdateByKeyRequest::class),
    ],

    \Commercetools\Core\Model\Project\Project::class => [
        'repository' => \BestIt\CommercetoolsODM\Repository\ProjectRepository::class
    ],

    \Commercetools\Core\Model\ShippingMethod\ShippingMethod::class => [
        'draft' => \Commercetools\Core\Model\ShippingMethod\ShippingMethodDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\ShippingMethods\ShippingMethodCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\ShippingMethods\ShippingMethodDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\ShippingMethods\ShippingMethodByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\ShippingMethods\ShippingMethodQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\ShippingMethods\ShippingMethodUpdateRequest::class),
    ]
];
