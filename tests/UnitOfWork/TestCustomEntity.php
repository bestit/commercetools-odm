<?php

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;
use Commercetools\Core\Model\Common\Address;

/**
 * Class TestCustomEntity
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork
 * @subpackage UnitOfWork
 */
class TestCustomEntity
{
    /**
     * The email of the user.
     *
     * @Commercetools\Field(type="array",collection="Commercetools\Core\Model\Common\AddressCollection")
     * @var Address[]
     */
    protected $addresses = [];

    /**
     * A test id.
     *
     * @Commercetools\Field(type="string")
     * @Commercetools\Id()
     * @var string|null
     */
    private $id;

    /**
     * Returns the addresses of this user.
     *
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * Returns the id oft his object.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the addresses of this user.
     *
     * @param Address[] $addresses
     *
     * @return TestCustomEntity
     */
    public function setAddresses(array $addresses): TestCustomEntity
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Sets the id of the object.
     *
     * @param string $id
     *
     * @return TestCustomEntity
     */
    public function setId(string $id): TestCustomEntity
    {
        $this->id = $id;

        return $this;
    }
}
