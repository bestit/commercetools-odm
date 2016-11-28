<?php

namespace BestIt\CommercetoolsODM\Entity;

/**
 * Class ReviewRatingStatistics
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class ReviewRatingStatistics
{
    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\AverageRating
     * @var int
     */
    private $averageRating = 0;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Count
     * @var int
     */
    private $count = 0;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\HighestRating
     * @var int
     */
    private $highestRating = 0;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\LowestRating
     * @var int
     */
    private $lowestRating = 0;

    /**
     * @Commercetools\Field(type="") = TODO JSONObject
     * @Commercetools\RatingDistribution
     * @var
     */
    private $ratingDistribution;

    /**
     * gets AverageRating
     *
     * @return mixed
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * gets Count
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * gets HighestRating
     *
     * @return int
     */
    public function getHighestRating(): int
    {
        return $this->highestRating;
    }

    /**
     * gets LowestRating
     *
     * @return int
     */
    public function getLowestRating(): int
    {
        return $this->lowestRating;
    }

    /**
     * gets RatingDistribution
     *
     * @return mixed
     */
    public function getRatingDistribution()
    {
        return $this->ratingDistribution;
    }

    /**
     * Sets AverageRating
     *
     * @param mixed $averageRating
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;
    }

    /**
     * Sets Count
     *
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * Sets HighestRating
     *
     * @param int $highestRating
     */
    public function setHighestRating(int $highestRating)
    {
        $this->highestRating = $highestRating;
    }

    /**
     * Sets LowestRating
     *
     * @param int $lowestRating
     */
    public function setLowestRating(int $lowestRating)
    {
        $this->lowestRating = $lowestRating;
    }

    /**
     * Sets RatingDistribution
     *
     * @param mixed $ratingDistribution
     */
    public function setRatingDistribution($ratingDistribution)
    {
        $this->ratingDistribution = $ratingDistribution;
    }
}
