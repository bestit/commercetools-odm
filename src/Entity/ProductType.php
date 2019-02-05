<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 *
 * @author lange <lange@bestit-online.de>
 * @Commercetools\DraftClass("Commercetools\Core\Model\ProductType\ProductTypeDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     create="ProductTypeCreateRequest",
 *     defaultNamespace="Commercetools\Core\Request\ProductTypes",
 *     deleteById="ProductTypeDeleteRequest",
 *     deleteByKey="ProductTypeDeleteByKeyRequest",
 *     findById="ProductTypeByIdGetRequest",
 *     findByKey="ProductTypeByKeyGetRequest",
 *     query="ProductTypeQueryRequest",
 *     updateById="ProductTypeUpdateRequest",
 *     updateByKey="ProductTypeUpdateByKeyRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ProductTypeRepository")
 * @package BestIt\CommercetoolsODM\Entity
 * @subpackage Entity
 */
class ProductType
{
    use BaseEntityTrait;
    use EntityWithKeyTrait;

    /**
     * The description for the type.
     *
     * @Commercetools\Field(type="string")
     * @var string
     */
    private $description = '';

    /**
     * The name for the type.
     *
     * @Commercetools\Field(type="string")
     * @var string
     */
    private $name = '';

    /**
     * Adds the attribute definition of there is a new one on update.
     *
     * @Commercetools\Update(
     *     class="Commercetools\Core\Request\ProductTypes\Command\ProductTypeAddAttributeDefinitionAction",
     *     method="ofAttribute",
     *     property=""
     * )
     * @param mixed $newValue
     * @param mixed $oldValue
     * @param string $actionClass
     *
     * @return array|AbstractAction|void
     */
    public function addAttributeDefinitionOnUpdate($newValue, $oldValue, string $actionClass)
    {
        // TODO
    }

    /**
     * Returns the description for the type.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the name for the type.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the description for the type.
     *
     * @param string $description
     *
     * @return ProductType
     */
    public function setDescription(string $description): ProductType
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the name for the type.
     *
     * @param string $name
     *
     * @return ProductType
     */
    public function setName(string $name): ProductType
    {
        $this->name = $name;

        return $this;
    }
}
