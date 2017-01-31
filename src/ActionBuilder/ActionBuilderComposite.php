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
        return $this->createUpdateActionsRecursively($metadata, $changedData, $oldData, $sourceObject, '');
    }

    /**
     * Iterates recursively through the field hierarchy and delivers their actions.
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param object $sourceObject
     * @param string $parentPath
     * @return array
     */
    private function createUpdateActionsRecursively(
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $parentPath = ''
    )
    {
        $actions = [];

        foreach ($changedData as $pathPart => $value) {
            if ($parentPath) {
                $parentPath = rtrim($parentPath, '/') . '/';
            }

            $path = $parentPath . $pathPart;

            // Todo Check on customer
            if ($metadata->isCustomTypeField($path)) {
                $path = 'custom/' . $path;
            }

            $builders = $this->getActionBuilderFactory()->getActionBuilders($metadata, $path, $sourceObject);

            foreach ($builders as $builder) {
                $nextActions = $builder->createUpdateActions(
                    $value,
                    $metadata,
                    $changedData,
                    $oldData,
                    $sourceObject
                );

                if ($nextActions) {
                    $actions = array_merge($actions, $nextActions);
                }
            }

            if (is_array($value)) {
                $subActions = $this->createUpdateActionsRecursively(
                    $metadata,
                    $value,
                    $oldData,
                    $sourceObject,
                    $path
                );

                if ($subActions) {
                    $actions = array_merge($actions, $subActions);
                }
            }
        }

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
