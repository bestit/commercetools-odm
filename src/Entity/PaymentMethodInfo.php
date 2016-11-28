<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class PaymentMethodInfo
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class PaymentMethodInfo
{
    /**
     * The payment method that is used, e.g. e.g. a conventional string representing Credit Card, Cash Advance etc.
     * @Commercetools\Field(type="string")
     * @Commercetools\Method
     * @var string
     */
    private $method;

    /**
     * A human-readable, localized name for the payment method, e.g. ‘Credit Card’.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;

    /**
     * The interface that handles the payment (usually a PSP). Cannot be changed once it has been set.
     * The combination of PaymentinterfaceId and this field must be unique.
     * @Commercetools\Field(type="string")
     * @Commercetools\PaymentInterface
     * @var string
     */
    private $paymentInterface;

    /**
     * gets Method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * gets Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * gets PaymentInterface
     *
     * @return string
     */
    public function getPaymentInterface(): string
    {
        return $this->paymentInterface;
    }

    /**
     * Sets Method
     *
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Sets Name
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets PaymentInterface
     *
     * @param string $paymentInterface
     */
    public function setPaymentInterface(string $paymentInterface)
    {
        $this->paymentInterface = $paymentInterface;
    }
}
