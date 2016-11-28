<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Transaction
 * A representation of a financial transactions. Transactions are either created by the
 * solution implementation to trigger a new transaction at the PSP or created by the
 * PSP integration as the result of a notification by the PSP.
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Transaction
{
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Amount
     * @var
     */
    private $amount;

    /**
     * The unique ID of this object.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * The identifier that is used by the interface that managed the transaction (usually the PSP).
     * If a matching interaction was logged in the interfaceInteractions array,
     * the corresponding interaction should be findable with this ID.
     * @Commercetools\Field(type="string")
     * @Commercetools\InteractionId
     * @var string
     */
    private $interactionId;

    /**
     * The state of this transaction.
     * @Commercetools\Field(type="") TODO TransactionState
     * @Commercetools\State
     * @var
     */
    private $state;

    /**
     * The time at which the transaction took place.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\Timestamp
     * @var \DateTime
     */
    private $timestamp;

    /**
     * The type of this transaction.
     * @Commercetools\Field(type="") TODO TransactionType
     * @Commercetools\Type
     * @var
     */
    private $type;

    /**
     * gets Amount
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
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
     * gets InteractionId
     *
     * @return string
     */
    public function getInteractionId(): string
    {
        return $this->interactionId;
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
     * gets Timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * gets Type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets Amount
     *
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * Sets InteractionId
     *
     * @param string $interactionId
     */
    public function setInteractionId(string $interactionId)
    {
        $this->interactionId = $interactionId;
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

    /**
     * Sets Timestamp
     *
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Sets Type
     *
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
