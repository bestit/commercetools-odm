<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * @author Paul Tenbrock <Paul_Tenbrock@outlook.com>
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
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class TaxCategoryDraft

{

    /**
     * The Name for the type.
     * @var string
     */
    private $name = '';
    /**
     * The Description for the type.
     * @var string
     */
    private $description = '';
    /**
     * The Rates for the type.
     * @var string
     */
    private $rates = '';


    /**
     * Returns the Name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the Description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the Rates for the type.
     * @return string
     */
    public function getRates(): string
    {
        return $this->rates;
    }


    /**
     * @param string $name
     * @return TaxCategoryDraft
     */
    public function setName(string $name): TaxCategoryDraft
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $description
     * @return TaxCategoryDraft
     */
    public function setDescription(string $description): TaxCategoryDraft
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $rates
     * @return TaxCategoryDraft
     */
    public function setRates(string $rates): TaxCategoryDraft
    {
        $this->rates = $rates;

        return $this;
    }

}