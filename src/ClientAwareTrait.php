<?php

namespace BestIt\CommercetoolsODM;

use Commercetools\Core\Client;

/**
 * Basic trait to help with the commercetools client.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
trait ClientAwareTrait
{
    /**
     * The commercetools client.
     * @var Client
     */
    private $client = null;

    /**
     * Returns the commercetools client.
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Sets the commercetools client.
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}
