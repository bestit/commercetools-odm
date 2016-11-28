<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Payments
 * Payments hold information about the current state of receiving and/or refunding money
 * A payment represents one or a logically connected series of financial transactions like reserving money,
 * charging money or refunding money. They serve as a representation of the current state of the payment and
 * can also be used to trigger new transactions. The actual financial process is not done by the commercetools™
 * platform but usually by a PSP (Payment Service Provider), which is connected
 * via PSP-specific integration implementation.
 * The Payment representation does not contain payment method-specific fields.
 * These are added as CustomFields via a payment method-specific payment type.
 * Payments are usually referenced by an order or a cart in their PaymentInfo Object.
 * They usually reference a Customer, unless they are part of an anonymous order.
 *
 * @Commercetools\DraftClass("Commercetools\Core\Model\Payment\PaymentDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Payments",
 *     findById="PaymentByIDGetRequest",
 *     query="PaymentQueryRequest",
 *     create="PaymentCreateRequest",
 *     update="PaymentUpdateRequest",
 *     delete="PaymentDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\PaymentRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class Payment
{
    /**
     * The amount of money that has been authorized (i.e. reliably reserved, but not transferred).
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\AmountAuthorized
     * @var
     */
    private $amountAuthorized;

    /**
     * The amount of money that has been received from the customer. This value is updated during the financial process.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\AmountPaid
     * @var
     */
    private $amountPaid;

    /**
     * How much money this payment intends to receive from the customer.
     * The value usually matches the cart or order gross total.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\AmountPlanned
     * @var
     */
    private $amountPlanned;

    /**
     * The amount of money that has been refunded to the customer.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\AmountRefunded
     * @var
     */
    private $amountRefunded;

    /**
     * Until when the authorization is valid. Can only be set when amountAuthorized is set, too.
     * @Commercetools\Field(type="string")
     * @Commercetools\AuthorizedUntil
     * @var string
     */
    private $authorizedUntil;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @Commercetools\Field(type="") TODO CustomFields
     * @Commercetools\Custom
     * @var
     */
    private $custom;

    /**
     * A reference to the customer this payment belongs to.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Customer
     * @var
     */
    private $customer;

    /**
     * This ID can be used as an additional identifier for external systems like the systems
     * involved in order or receivables management.
     * @Commercetools\Field(type="string")
     * @Commercetools\ExternalId
     * @var string
     */
    private $externalId;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * The identifier that is used by the interface that manages the payment (usually the PSP).
     * Cannot be changed once it has been set. The combination of this ID and
     * the PaymentMethodInfo paymentInterface must be unique.
     * @Commercetools\Field(type="string")
     * @Commercetools\InterfaceId
     * @var string
     */
    private $interfaceId;

    /**
     * Interface interactions can be requests sent to the PSP, responses received from the PSP or notifications
     * received from the PSP. Some interactions may result in a transaction. If so, the interactionId in the Transaction
     * should be set to match the ID of the PSP for the interaction. Interactions are managed by the PSP integration
     * and are usually neither written nor read by the user facing frontends or other services.
     * @Commercetools\Field(type="array")
     * @Commercetools\InterfaceInteractions
     * @var array
     */
    private $interfaceInteractions;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * @Commercetools\Field(type="") TODO PaymentMethodInfo
     * @Commercetools\PaymentMethodInfo
     * @var
     */
    private $paymentMethodInfo;

    /**
     * @Commercetools\Field(type="") TODO PaymentStatus
     * @Commercetools\PaymentStatus
     * @var
     */
    private $paymentStatus;

    /**
     * Array of Transaction A list of financial transactions of different TransactionTypes
     * with different TransactionStates.
     * @Commercetools\Field(type="array")
     * @Commercetools\Transactions
     * @var array
     */
    private $transactions;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version;

    /**
     * gets AmountAuthorized
     *
     * @return mixed
     */
    public function getAmountAuthorized()
    {
        return $this->amountAuthorized;
    }

    /**
     * gets AmountPaid
     *
     * @return mixed
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * gets AmountPlanned
     *
     * @return mixed
     */
    public function getAmountPlanned()
    {
        return $this->amountPlanned;
    }

    /**
     * gets AmountRefunded
     *
     * @return mixed
     */
    public function getAmountRefunded()
    {
        return $this->amountRefunded;
    }

    /**
     * gets AuthorizedUntil
     *
     * @return string
     */
    public function getAuthorizedUntil(): string
    {
        return $this->authorizedUntil;
    }

    /**
     * gets CreatedAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * gets Custom
     *
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * gets Customer
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * gets ExternalId
     *
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * gets Id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * gets InterfaceId
     *
     * @return string
     */
    public function getInterfaceId(): string
    {
        return $this->interfaceId;
    }

    /**
     * gets InterfaceInteractions
     *
     * @return array
     */
    public function getInterfaceInteractions(): array
    {
        return $this->interfaceInteractions;
    }

    /**
     * gets LastModifiedAt
     *
     * @return \DateTime
     */
    public function getLastModifiedAt(): \DateTime
    {
        return $this->lastModifiedAt;
    }

    /**
     * gets PaymentMethodInfo
     *
     * @return mixed
     */
    public function getPaymentMethodInfo()
    {
        return $this->paymentMethodInfo;
    }

    /**
     * gets PaymentStatus
     *
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * gets Transactions
     *
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * gets Version
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Sets AmountAuthorized
     *
     * @param mixed $amountAuthorized
     */
    public function setAmountAuthorized($amountAuthorized)
    {
        $this->amountAuthorized = $amountAuthorized;
    }

    /**
     * Sets AmountPaid
     *
     * @param mixed $amountPaid
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;
    }

    /**
     * Sets AmountPlanned
     *
     * @param mixed $amountPlanned
     */
    public function setAmountPlanned($amountPlanned)
    {
        $this->amountPlanned = $amountPlanned;
    }

    /**
     * Sets AmountRefunded
     *
     * @param mixed $amountRefunded
     */
    public function setAmountRefunded($amountRefunded)
    {
        $this->amountRefunded = $amountRefunded;
    }

    /**
     * Sets AuthorizedUntil
     *
     * @param string $authorizedUntil
     */
    public function setAuthorizedUntil(string $authorizedUntil)
    {
        $this->authorizedUntil = $authorizedUntil;
    }

    /**
     * Sets CreatedAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Sets Custom
     *
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Sets Customer
     *
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Sets ExternalId
     *
     * @param string $externalId
     */
    public function setExternalId(string $externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * Sets Id
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Sets InterfaceId
     *
     * @param string $interfaceId
     */
    public function setInterfaceId(string $interfaceId)
    {
        $this->interfaceId = $interfaceId;
    }

    /**
     * Sets InterfaceInteractions
     *
     * @param array $interfaceInteractions
     */
    public function setInterfaceInteractions(array $interfaceInteractions)
    {
        $this->interfaceInteractions = $interfaceInteractions;
    }

    /**
     * Sets LastModifiedAt
     *
     * @param \DateTime $lastModifiedAt
     */
    public function setLastModifiedAt(\DateTime $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }

    /**
     * Sets PaymentMethodInfo
     *
     * @param mixed $paymentMethodInfo
     */
    public function setPaymentMethodInfo($paymentMethodInfo)
    {
        $this->paymentMethodInfo = $paymentMethodInfo;
    }

    /**
     * Sets PaymentStatus
     *
     * @param mixed $paymentStatus
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * Sets Transactions
     *
     * @param array $transactions
     */
    public function setTransactions(array $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Sets Version
     *
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }
}
