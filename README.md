# bestit/commercetools-odm

Wraps the [commercetools/php-sdk](https://github.com/commercetools/commercetools-php-sdk) with the 
[doctrine common api](https://github.com/doctrine/common).

## Installation

### Step 1: Download

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bestit/commercetools-odm
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable

The central script of the odm is the **BestIt\CommercetoolsODM\DocumentManager** based on the 
_Doctrine\Common\Persistence\ObjectManager_. Just fill it with its dependencies and you are good to go.

You could use our [bestit/commercetools-odm-bundle](https://github.com/bestit/commercetools-odm-bundle) to kickstart 
your project.

## Usage

A small example usage:

```php
<?php

// Load the product repository.
/** @var \BestIt\CommercetoolsODM\DocumentManagerInterface $documentManager */
/** @var \BestIt\CommercetoolsODM\Repository\ProductRepository $repository */
$repository = $documentManager->getRepository(\Commercetools\Core\Model\Product\Product::class);

// Fetch it from the database or create a new instance.
/** @var \Commercetools\Core\Model\Product\Product $product */
$product = $repository->findByKey($key) ??
    $documentManager->getClassMetadata(\Commercetools\Core\Model\Product\Product::class)->getNewInstance();

// Do your work.
if (!$product->getId()) {
    $product
        ->setProductType(\Commercetools\Core\Model\ProductType\ProductTypeReference::ofId('type'))
        // ....
}

// You get automatic but simple 409 conflict resolution if you use  this callback. If not, 409s lead to an exception. 
$documentManager->modify($product, function(\Commercetools\Core\Model\Product\Product $product) {
    $product->setKey('new-key');
});

// Persist the changes
$documentManager->persist($product);

// Detach it from the document manager (UnitOfWork) after flush.
$documentManager->detachDeferred($product);

// Flush the changes in the document manager to the repository.
$documentManager->flush();

```

## Contribute

Some advices to contribute to this library ...

### Metadata

This is the main problem of this library: How to map the structure of the commercetools sdk to a unified api. We 
resolved it for the moment with a map:

```php
<?php
// ./src/Resources/config/metadata.php

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

return [
    // Your model
    \Commercetools\Core\Model\Cart\Cart::class => [
        // The draft class for creating a new model
        'draft' => \Commercetools\Core\Model\Cart\CartDraft::class,
        // Which repo to use
        'repository' => \BestIt\CommercetoolsODM\Repository\CartRepository::class,
        // And the map for every needed request type.
        'requestClassMap' => (new RequestMap())
            ->setCreate(\Commercetools\Core\Request\Carts\CartCreateRequest::class)
            ->setDeleteById(\Commercetools\Core\Request\Carts\CartDeleteRequest::class)
            ->setFindById(\Commercetools\Core\Request\Carts\CartByIdGetRequest::class)
            ->setFindByCustomerId(\Commercetools\Core\Request\Carts\CartByCustomerIdGetRequest::class)
            ->setQuery(\Commercetools\Core\Request\Carts\CartQueryRequest::class)
            ->setUpdateById(\Commercetools\Core\Request\Carts\CartUpdateRequest::class)
    ],

    // ... map more models
];
```

### Action Builder

Even if we work object oriented, we still support the 
[partial updates of commercetools](https://dev.commercetools.com/http-api.html#partial-updates). Please consult the 
interface **BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderInterface** to add your own action builder. 
The action builders create the request actions to commercetools matching the changed property values.
 
 **Reset your cache, after changing the action builder setup.**

## Further steps

* Work with less assumptions, for example, every standard model has an id and version 
* Remove hard coupling to the publish marker on the product to publish the staged version
* Add every action builder. Not every thing is there at the moment.
* More unittesting
* More documentation
* Harmonize naming: Move away from "documents" to "objects"
