<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * How to update the given field?
 *
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping\Annotations
 * @subpackage Mapping\Annotations
 * @Target({"METHOD","PROPERTY"})
 */
class Update implements Annotation
{
    /**
     * The method name to create (the request|an array of requests).
     *
     * @var callable
     */
    public $callback = '';

    /**
     * The class name for the update action.
     *
     * @Required
     * @var string
     */
    public $class = '';

    /**
     * The constructor method for the update class.
     *
     * @Required
     * @var string
     */
    public $method = '';

    /**
     * Overwrites the property for which this update annotation is used.
     *
     * @var string
     */
    public $property = '';

    /**
     * Returns the method name to create (the request|an array of requests).
     *
     * @return string
     */
    public function getCallback(): string
    {
        return $this->callback;
    }

    /**
     * Returns the class name for the update action.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Returns the constructor method for the update class.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Maps this update annotation on a property.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * Sets the method name to create (the request|an array of requests).
     *
     * @param callable $callback
     *
     * @return Update
     */
    public function setCallback(callable $callback): Update
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Sets the property for which this
     *
     * @param string $property
     *
     * @return Update
     */
    public function setProperty(string $property): Update
    {
        $this->property = $property;
        return $this;
    }
}
