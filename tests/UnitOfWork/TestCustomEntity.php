<?php

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;
use Commercetools\Core\Model\Common\Address;

/**
 * Class TestCustomEntity
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage UnitOfWork
 * @version $id$
 */
class TestCustomEntity
{
    /**
     * The email of the user.
     * @Commercetools\Field(type="array",collection="Commercetools\Core\Model\Common\AddressCollection")
     * @var Address[]
     */
    protected $addresses = [];

    /**
     * Returns the addresses of this user.
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * Sets the addresses of this user.
     * @param Address[] $addresses
     * @return TestCustomEntity
     */
    public function setAddresses(array $addresses): TestCustomEntity
    {
        $this->addresses = $addresses;

        return $this;
    }
}
