<?php

namespace BestIt\CommercetoolsODM\Entity;

trait EntityWithKeyTrait
{
    /**
     * The key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @Commercetools\Update(
     *     class="Commercetools\Core\Request\ProductTypes\Command\ProductTypeSetKeyAction",
     *     method="ofKey"
     * )
     * @var string
     */
    private $key = '';

    /**
     * Returns the key for the type.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the key for the type.
     * @param string $key
     * @return ProductType
     */
    public function setKey(string $key): ProductType
    {
        $this->key = $key;

        return $this;
    }
}
