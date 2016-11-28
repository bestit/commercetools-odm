<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Address
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Address
{
    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Title
     * @var string
     */
    private $title;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Salutation
     * @var string
     */
    private $salutation;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\FirstName
     * @var string
     */
    private $firstName;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\LastName
     * @var string
     */
    private $lastName;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\StreetName
     * @var string
     */
    private $streetName;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\StreetNumber
     * @var string
     */
    private $streetNumber;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\AdditionalStreetInfo
     * @var string
     */
    private $additionalStreetInfo;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\PostalCode
     * @var string
     */
    private $postalCode;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\City
     * @var string
     */
    private $city;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Region
     * @var string
     */
    private $region;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\State
     * @var string
     */
    private $state;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Company
     * @var string
     */
    private $company;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Department
     * @var string
     */
    private $department;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Building
     * @var string
     */
    private $building;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Apartment
     * @var string
     */
    private $apartment;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\POBox
     * @var string
     */
    private $pOBox;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Phone
     * @var string
     */
    private $phone;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Mobile
     * @var string
     */
    private $mobile;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Email
     * @var string
     */
    private $email;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Fax
     * @var string
     */
    private $fax;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\AdditionalAddressInfo
     * @var string
     */
    private $additionalAddressInfo;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\ExternalId
     * @var string
     */
    private $externalId;

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
     * Sets Id
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * gets Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets Title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * gets Salutation
     *
     * @return string
     */
    public function getSalutation(): string
    {
        return $this->salutation;
    }

    /**
     * Sets Salutation
     *
     * @param string $salutation
     */
    public function setSalutation(string $salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * gets FirstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Sets FirstName
     *
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * gets LastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Sets LastName
     *
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * gets StreetName
     *
     * @return string
     */
    public function getStreetName(): string
    {
        return $this->streetName;
    }

    /**
     * Sets StreetName
     *
     * @param string $streetName
     */
    public function setStreetName(string $streetName)
    {
        $this->streetName = $streetName;
    }

    /**
     * gets StreetNumber
     *
     * @return string
     */
    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    /**
     * Sets StreetNumber
     *
     * @param string $streetNumber
     */
    public function setStreetNumber(string $streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * gets AdditionalStreetInfo
     *
     * @return string
     */
    public function getAdditionalStreetInfo(): string
    {
        return $this->additionalStreetInfo;
    }

    /**
     * Sets AdditionalStreetInfo
     *
     * @param string $additionalStreetInfo
     */
    public function setAdditionalStreetInfo(string $additionalStreetInfo)
    {
        $this->additionalStreetInfo = $additionalStreetInfo;
    }

    /**
     * gets PostalCode
     *
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * Sets PostalCode
     *
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * gets City
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets City
     *
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * gets Region
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * Sets Region
     *
     * @param string $region
     */
    public function setRegion(string $region)
    {
        $this->region = $region;
    }

    /**
     * gets State
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Sets State
     *
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * gets Country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets Country
     *
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * gets Company
     *
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Sets Company
     *
     * @param string $company
     */
    public function setCompany(string $company)
    {
        $this->company = $company;
    }

    /**
     * gets Department
     *
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * Sets Department
     *
     * @param string $department
     */
    public function setDepartment(string $department)
    {
        $this->department = $department;
    }

    /**
     * gets Building
     *
     * @return string
     */
    public function getBuilding(): string
    {
        return $this->building;
    }

    /**
     * Sets Building
     *
     * @param string $building
     */
    public function setBuilding(string $building)
    {
        $this->building = $building;
    }

    /**
     * gets Apartment
     *
     * @return string
     */
    public function getApartment(): string
    {
        return $this->apartment;
    }

    /**
     * Sets Apartment
     *
     * @param string $apartment
     */
    public function setApartment(string $apartment)
    {
        $this->apartment = $apartment;
    }

    /**
     * gets POBox
     *
     * @return string
     */
    public function getPOBox(): string
    {
        return $this->pOBox;
    }

    /**
     * Sets POBox
     *
     * @param string $pOBox
     */
    public function setPOBox(string $pOBox)
    {
        $this->pOBox = $pOBox;
    }

    /**
     * gets Phone
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Sets Phone
     *
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * gets Mobile
     *
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * Sets Mobile
     *
     * @param string $mobile
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * gets Email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets Email
     *
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * gets Fax
     *
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * Sets Fax
     *
     * @param string $fax
     */
    public function setFax(string $fax)
    {
        $this->fax = $fax;
    }

    /**
     * gets AdditionalAddressInfo
     *
     * @return string
     */
    public function getAdditionalAddressInfo(): string
    {
        return $this->additionalAddressInfo;
    }

    /**
     * Sets AdditionalAddressInfo
     *
     * @param string $additionalAddressInfo
     */
    public function setAdditionalAddressInfo(string $additionalAddressInfo)
    {
        $this->additionalAddressInfo = $additionalAddressInfo;
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
     * Sets ExternalId
     *
     * @param string $externalId
     */
    public function setExternalId(string $externalId)
    {
        $this->externalId = $externalId;
    }
}
