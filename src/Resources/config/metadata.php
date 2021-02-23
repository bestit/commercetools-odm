<?php

// phpcs:ignoreFile

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

$additionalMetaData = [];

// Supported by Commercetools SDK ^2.8
if (class_exists('Commercetools\Core\Model\Store\Store')) {
    $additionalMetaData[\Commercetools\Core\Model\Store\Store::class] = [
        'draft' => \Commercetools\Core\Model\Store\StoreDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Stores\StoreCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Stores\StoreDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Stores\StoreByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Stores\StoreQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Stores\StoreUpdateRequest::class)
    ];
}

return array_merge([
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
            ->setFindByKey(\Commercetools\Core\Request\Categories\CategoryByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Categories\CategoryQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Categories\CategoryUpdateRequest::class)
    ],

    \Commercetools\Core\Model\TaxCategory\TaxCategory::class => [
        'draft' => \Commercetools\Core\Model\TaxCategory\TaxCategoryDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\TaxCategories\TaxCategoryCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\TaxCategories\TaxCategoryDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\TaxCategories\TaxCategoryByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\TaxCategories\TaxCategoryByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\TaxCategories\TaxCategoryQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\TaxCategories\TaxCategoryUpdateRequest::class)
    ],

    \Commercetools\Core\Model\Channel\Channel::class => [
        'draft' => \Commercetools\Core\Model\Channel\ChannelDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Repository\ChannelRepository::class,
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
            ->setUpdateById(\Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest::class)
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

    \Commercetools\Core\Model\Inventory\InventoryEntry::class => [
        'draft' => \Commercetools\Core\Model\Inventory\InventoryDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\DefaultRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Inventory\InventoryCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Inventory\InventoryDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Inventory\InventoryByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Inventory\InventoryQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Inventory\InventoryUpdateRequest::class)
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
    ],

    \Commercetools\Core\Model\ShoppingList\ShoppingList::class => [
        'draft' => \Commercetools\Core\Model\ShoppingList\ShoppingListDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\DefaultRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\ShoppingLists\ShoppingListCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\ShoppingLists\ShoppingListDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\ShoppingLists\ShoppingListDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\ShoppingLists\ShoppingListByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\ShoppingLists\ShoppingListByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\ShoppingLists\ShoppingListQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\ShoppingLists\ShoppingListUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\ShoppingLists\ShoppingListUpdateByKeyRequest::class),
    ],

    \Commercetools\Core\Model\Subscription\Subscription::class => [
        'draft' => \Commercetools\Core\Model\Subscription\SubscriptionDraft::class,
        'repository' => \BestIt\CommercetoolsODM\Model\DefaultRepository::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Subscriptions\SubscriptionCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Subscriptions\SubscriptionDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\Subscriptions\SubscriptionDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\Subscriptions\SubscriptionByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\Subscriptions\SubscriptionByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Subscriptions\SubscriptionQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Subscriptions\SubscriptionUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\Subscriptions\SubscriptionUpdateByKeyRequest::class),
    ],

    \Commercetools\Core\Model\Zone\Zone::class => [
        'draft' => \Commercetools\Core\Model\Zone\ZoneDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Zones\ZoneCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Zones\ZoneDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Zones\ZoneByIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Zones\ZoneQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Zones\ZoneUpdateRequest::class),
    ],

    \Commercetools\Core\Model\Payment\Payment::class => [
        'draft' => \Commercetools\Core\Model\Payment\PaymentDraft::class,
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Payments\PaymentCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Payments\PaymentDeleteRequest::class)
            ->setDeleteByKey(\Commercetools\Core\Request\Payments\PaymentDeleteByKeyRequest::class)
            ->setFindById(\Commercetools\Core\Request\Payments\PaymentByIdGetRequest::class)
            ->setFindByKey(\Commercetools\Core\Request\Payments\PaymentByKeyGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Payments\PaymentQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Payments\PaymentUpdateRequest::class)
            ->setUpdateByKey(\Commercetools\Core\Request\Payments\PaymentUpdateByKeyRequest::class),
    ]
], $additionalMetaData);
