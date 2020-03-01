<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;

/**
 * Processes the action builders for a changed object.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder
 * @subpackage ActionBuilder
 */
class ActionBuilderComposite implements ActionBuilderProcessorInterface
{
    /**
     * The factory to get the action builders.
     *
     * @var ActionBuilderFactoryInterface
     */
    private $actionBuilderFactory = null;

    /**
     * ActionBuilderComposite constructor.
     *
     * @param ActionBuilderFactoryInterface $actionBuilderFactory
     */
    public function __construct(ActionBuilderFactoryInterface $actionBuilderFactory)
    {
        $this->setActionBuilderFactory($actionBuilderFactory);
    }

    /**
     * Creates the update action for the gievn class and data.
     *
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return ActionBuilderInterface[]
     */
    public function createUpdateActions(
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $actions = $this->createUpdateActionsRecursively($metadata, $changedData, $oldData, $sourceObject, '');

        // We will merge all prioritize array actions into one big array.
        // Actions with the highest priority will be first, lowest will be last
        krsort($actions);
        $actions = call_user_func_array('array_merge', $actions);

        return $actions;
    }

    /**
     * Iterates recursively through the field hierarchy and delivers their actions.
     *
     * Return an array with priority as key and a collection of actions. Example:
     * [
     *      200 => [Action1, Action5],
     *        0 => [Action3, Action2],
     *       -5 => [Action4]
     * ]
     *
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @param string $parentPath
     *
     * @return array
     */
    private function createUpdateActionsRecursively(
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $parentPath = ''
    ): array {

        $actions = [];

        foreach ($changedData as $pathPart => $value) {
            if ($parentPath) {
                $parentPath = rtrim($parentPath, '/') . '/';
            }

            $path = $parentPath . $pathPart;

            // Simulate the native structure out of a custom type declared as a normal property
            if ($metadata->isCustomTypeField($path)) {
                $path = 'custom/fields/' . $path;
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
                    $actions[$builder->getPriority()] = array_merge(
                        $actions[$builder->getPriority()] ?? [],
                        $nextActions
                    );
                }
            }

            if (is_array($value)) {
                $subNextActions = $this->createUpdateActionsRecursively(
                    $metadata,
                    $value,
                    $oldData,
                    $sourceObject,
                    $path
                );

                if ($subNextActions) {
                    foreach ($subNextActions as $priority => $subActions) {
                        $actions[$priority] = array_merge($actions[$priority] ?? [], $subActions);
                    }
                }
            }
        }

        return $actions;
    }

    /**
     * Returns the factory to get the action builders.
     *
     * @return ActionBuilderFactoryInterface
     */
    private function getActionBuilderFactory(): ActionBuilderFactoryInterface
    {
        return $this->actionBuilderFactory;
    }

    /**
     * Sets the factory to get the action builders.
     *
     * @param ActionBuilderFactoryInterface $actionBuilderFactory
     *
     * @return ActionBuilderProcessorInterface
     */
    private function setActionBuilderFactory(
        ActionBuilderFactoryInterface $actionBuilderFactory
    ): ActionBuilderProcessorInterface {
        $this->actionBuilderFactory = $actionBuilderFactory;

        return $this;
    }
}
