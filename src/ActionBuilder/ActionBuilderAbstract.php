<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

/**
 * Base class for building an update action.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
abstract class ActionBuilderAbstract implements ActionBuilderInterface
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '';

    /**
     * The field name.
     * @var string
     */
    protected $fieldName = '';

    /**
     * Allows this action other actions?
     * @var bool
     */
    protected $isStackable = true;

    /**
     * Caches the last support match.
     * @var array
     */
    private $lastFoundMatch = [];

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = '';

    /**
     * At which order should this builder be executed? Highest happens first.
     * @var int
     */
    protected $priority = 0;

    /**
     * Returns the complex field filter with slash delimiters if there is one.
     * @return string
     */
    protected function getComplexFieldFilter(): string
    {
        return $this->complexFieldFilter
            ? sprintf('%s%s%s', self::FILTER_DELIMITER, $this->complexFieldFilter, self::FILTER_DELIMITER)
            : '';
    }

    /**
     * Returns the name of the field.
     * @return string
     */
    protected function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * Returns the last support()-match.
     * @return array
     */
    protected function getLastFoundMatch(): array
    {
        return $this->lastFoundMatch;
    }

    /**
     * For which class is this description used?
     * @return string
     */
    protected function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * At which order should this builder be executed? Highest happens first.
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Allows this action other actions?
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function isStackable(bool $newStatus = false): bool
    {
        $oldStatus = $this->isStackable;

        if (func_num_args()) {
            $this->isStackable = $newStatus;
        }

        return $oldStatus;
    }

    /**
     * Sets the PCRE to match the hierarchical field path.
     * @param string $complexFieldFilter
     * @return ActionBuilderAbstract
     */
    protected function setComplexFieldFilter(string $complexFieldFilter): ActionBuilderAbstract
    {
        $this->complexFieldFilter = $complexFieldFilter;

        return $this;
    }

    /**
     * Sets the field name.
     * @param string $fieldName
     * @return ActionBuilderAbstract
     */
    protected function setFieldName(string $fieldName): ActionBuilderAbstract
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    /**
     * Caches the last support match.
     * @param array $lastFoundMatch
     * @return ActionBuilderAbstract
     */
    protected function setLastFoundMatch(array $lastFoundMatch): ActionBuilderAbstract
    {
        $this->lastFoundMatch = $lastFoundMatch;

        return $this;
    }
    
    /**
     * For which class is this description used?
     * @param string $modelClass
     * @return ActionBuilderAbstract
     */
    protected function setModelClass(string $modelClass): ActionBuilderAbstract
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * Returns true if the given class name matches the model class for this description.
     * @param string $fieldPath The hierarchical path of the fields.
     * @param string $referenceClass
     * @return bool|array If there is a complex match, the matched values are returned.
     */
    public function supports(string $fieldPath, string $referenceClass)
    {
        $supports = false;

        if ($this->getModelClass() === $referenceClass) {
            if (!$filter = $this->getComplexFieldFilter()) {
                $filter = sprintf(
                    '%s%s(\/?\*|$)%s',
                    self::FILTER_DELIMITER,
                    preg_quote($this->getFieldName(), self::FILTER_DELIMITER),
                    self::FILTER_DELIMITER
                );
            }

            $supports = preg_match($filter, $fieldPath, $matches) === 1;

            if ($supports) {
                $this->setLastFoundMatch($matches);
            }
        }

        return $supports;
    }
}
