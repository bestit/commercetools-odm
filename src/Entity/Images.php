<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Images
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Images
{
    /**
     * {"w": Number, "h": Number} <- Format
     * Dimensions of the original image. This can be used by your application e.g.
     * to determine whether the image is large enough to display a zoom view.
     * @Commercetools\Field(type="string")
     * @Commercetools\Dimensions
     * @var string
     */
    private $dimensions;
    /**
     * Custom label that can be used, for example, as an image description.
     * @Commercetools\Field(type="string")
     * @Commercetools\Label
     * @var string
     */
    private $label;
    /**
     * URL of the image in its original size. This can be used to obtain the image in different sizes
     * Images in specific sizes are obtained by appending a size suffix to the
     * original URL (before the .jpg, .png etc. extension part of the file name):
     * -thumb (50x50)
     * -small (150x150)
     * -medium (400x400)
     * -large (700x700)
     * -zoom (1400x1400)
     * the original size of the uploaded image is provided without a suffix
     * Note that images will never be scaled up. If the original image is tiny, it will keep its original size,
     * even in the “large” and “zoom” sizes. Also note that images are not shared between variants -
     * each variant has its own set of images. However, if you wish to have many variants with the same set of images,
     * you can implement this in your application by always showing the images of the master variant,
     * regardless of the selected variant.
     * @Commercetools\Field(type="string")
     * @Commercetools\Url
     * @var string
     */
    private $url;

    /**
     * gets Dimensions
     *
     * @return string
     */
    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    /**
     * gets Label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * gets Url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets Dimensions
     *
     * @param string $dimensions
     */
    public function setDimensions(string $dimensions)
    {
        $this->dimensions = $dimensions;
    }

    /**
     * Sets Label
     *
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * Sets Url
     *
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
}
