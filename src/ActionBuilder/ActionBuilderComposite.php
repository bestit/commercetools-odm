<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;

/**
 * Processes the action builders for a changed object.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder
 * @version $id$
 */
class ActionBuilderComposite implements ActionBuilderProcessorInterface
{
    /**
     * The factory to get the action builders.
     * @var ActionBuilderFactoryInterface
     */
    private $actionBuilderFactory = null;

    /**
     * ActionBuilderComposite constructor.
     * @param ActionBuilderFactoryInterface $actionBuilderFactory
     */
    public function __construct(ActionBuilderFactoryInterface $actionBuilderFactory)
    {
        $this->setActionBuilderFactory($actionBuilderFactory);
    }

    /**
     * Creates the update action for the gievn class and data.
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param object $sourceObject
     * @return ActionBuilderInterface[]
     */
    public function createUpdateActions(
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array
    {
        $actions = [];

        array_walk(
            $changedData,
            function ($value, $fieldName) use (&$actions, $metadata, $changedData, $oldData, $sourceObject) {
                $action = null;
                $subFieldName = '';

                if ($metadata->isCustomTypeField($fieldName)) {
                    $subFieldName = $fieldName;
                    $fieldName = 'custom';
                }

                $builders = $this->getActionBuilderFactory()->getActionBuilders($metadata, $fieldName, $sourceObject);

                foreach ($builders as $builder) {
                    $nextActions = $builder->createUpdateActions(
                        $value,
                        $metadata,
                        $changedData,
                        $oldData,
                        $sourceObject,
                        $subFieldName
                    );

                    if ($nextActions) {
                        $actions = array_merge($actions, $nextActions);

                        if (!$builder->isStackable()) {
                            break;
                        }
                    }
                }
            }
        );

        return $actions;
    }

    /**
     * Returns the factory to get the action builders.
     * @return ActionBuilderFactoryInterface
     */
    private function getActionBuilderFactory(): ActionBuilderFactoryInterface
    {
        return $this->actionBuilderFactory;
    }

    /**
     * Sets the factory to get the action builders.
     * @param ActionBuilderFactoryInterface $actionBuilderFactory
     * @return ActionBuilderProcessorInterface
     */
    private function setActionBuilderFactory(ActionBuilderFactoryInterface $actionBuilderFactory):
    ActionBuilderProcessorInterface
    {
        $this->actionBuilderFactory = $actionBuilderFactory;

        return $this;
    }
}
