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
     * The field name.
     * @var string
     */
    protected $fieldName = '';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = '';

    /**
     * Allows this action other actions?
     * @var bool
     */
    protected $isStackable = true;

    /**
     * At which order should this builder be executed? Highest happens first.
     * @var int
     */
    protected $priority = 0;

    /**
     * Returns the name of the field.
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
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
     * @param string $fieldName
     * @param string $referenceClass
     * @return bool
     */
    public function supports(string $fieldName, string $referenceClass): bool
    {
        return $this->getModelClass() === $referenceClass && $this->getFieldName() === $fieldName;
    }
}
