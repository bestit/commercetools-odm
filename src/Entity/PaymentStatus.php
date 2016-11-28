<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class PaymentStatus
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class PaymentStatus
{
    /**
     * A code describing the current status returned by the interface that processes the payment.
     * @Commercetools\Field(type="string")
     * @Commercetools\InterfaceCode
     * @var string
     */
    private $interfaceCode;

    /**
     * A text describing the current status returned by the interface that processes the payment.
     * @Commercetools\Field(type="string")
     * @Commercetools\InterfaceText
     * @var string
     */
    private $interfaceText;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\State
     * @var
     */
    private $state;

    /**
     * gets InterfaceCode
     *
     * @return string
     */
    public function getInterfaceCode(): string
    {
        return $this->interfaceCode;
    }

    /**
     * gets InterfaceText
     *
     * @return string
     */
    public function getInterfaceText(): string
    {
        return $this->interfaceText;
    }

    /**
     * gets State
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets InterfaceCode
     *
     * @param string $interfaceCode
     */
    public function setInterfaceCode(string $interfaceCode)
    {
        $this->interfaceCode = $interfaceCode;
    }

    /**
     * Sets InterfaceText
     *
     * @param string $interfaceText
     */
    public function setInterfaceText(string $interfaceText)
    {
        $this->interfaceText = $interfaceText;
    }

    /**
     * Sets State
     *
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
}
