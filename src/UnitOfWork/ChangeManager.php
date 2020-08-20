<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Common\Resource;
use Countable;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use SplObjectStorage;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_pad;
use function get_class;
use function is_array;
use function is_numeric;
use function is_object;
use function is_scalar;
use function is_string;
use function ltrim;
use function memory_get_usage;
use function method_exists;
use function preg_match;
use function sha1;
use function ucfirst;

/**
 * Checks if a model is changed and provides a consumer with changed data.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork
 */
class ChangeManager implements ChangeManagerInterface
{
    use DocumentManagerAwareTrait;
    use LoggerAwareTrait;

    /**
     * Cache of the raw data of models.
     *
     * @var SplObjectStorage
     */
    private $objectStates;

    /**
     * ChangeManager constructor.
     *
     * @param DocumentManagerInterface $documentManager
     */
    public function __construct(DocumentManagerInterface $documentManager)
    {
        $this->setDocumentManager($documentManager);

        $this->logger = new NullLogger();
        $this->objectStates = new SplObjectStorage();
    }

    /**
     * Is the given mocdel managed with this object?
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function contains($model): bool
    {
        return $this->objectStates->contains($model);
    }

    /**
     * How many objects are managed with this object?
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->objectStates);
    }

    /**
     * Remove the status of the given model from this object.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function detach($model)
    {
        return $this->objectStates->detach($model);
    }

    /**
     * Extracts the changes of the two arrays.
     *
     * @param array $newData
     * @param array|string|mixed $oldData
     * @param string $parentKey The hierarchy key for the parent of the checked data.
     *
     * @return array
     */
    private function extractChanges(array $newData, $oldData, string $parentKey = ''): array
    {
        if (is_scalar($oldData)) {
            return $newData;
        }

        $changedData = [];

        foreach ($newData as $key => $value) {
            // We use the name attribute to check for a nested set, because we do not have something else.
            $isValueArray = is_array($value);
            $oldDataHasKey = array_key_exists($key, $oldData);
            $oldValue = $oldDataHasKey ? $oldData[$key] : [];

            if ($isValueArray && $this->hasNestedArray($value)) {
                $changedSubData = $this->extractChanges($value, $oldValue, ltrim($parentKey . '/' . $key, '/'));

                // We think that an empty value can be ignored, except if we want to _add_ a new value.
                if ($changedSubData || !$oldDataHasKey) {
                    $changedData[$key] = $changedSubData;
                }

                // Sometimes the sdk parses an int to float.
            } else {
                if ((!$oldDataHasKey) || (($value !== $oldValue) && (!is_numeric($value) || ((float) $value !== (float) $oldValue)))) { // phpcs:ignore
                    $changedData[$key] = $value;

                    if ($isValueArray && is_array($oldValue) && (count($value) < count($oldValue))) {
                        $changedData[$key] = array_pad($changedData[$key], count($oldValue), null);
                    }
                }
            }

            // Remove the value from the old data to get a correct clean up.
            if (array_key_exists($key, $changedData)) {
                unset($oldData[$key]);
            }
        }

        // Mark everything as removed, which is in the old, but not in the new data.
        foreach (array_keys($oldData) as $key) {
            if (!array_key_exists($key, $newData)) {
                $changedData[$key] = null;
            }
        }

        return $changedData;
    }

    /**
     * Returns the metadata for the given class.
     *
     * @param string|object $class
     *
     * @return ClassMetadataInterface
     */
    private function getClassMetadata($class): ClassMetadataInterface
    {
        return $this->getDocumentManager()->getClassMetadata(
            is_string($class) ? $class : get_class($class)
        );
    }

    /**
     * Uses the getter of the sourceTarget to fetch the field names of the metadata.
     *
     * @param mixed $model
     *
     * @return array
     */
    private function extractData($model): array
    {
        $return = [];

        if (method_exists($model, 'toArray')) {
            /** @var JsonObject $model */
            $return = $model->toArray();
        } else {
            $metadata = $this->getClassMetadata($model);

            array_map(
                function ($field) use (&$return, $model) {
                    $fieldValue = $model->{'get' . ucfirst($field)}();

                    $return[$field] = is_object($fieldValue) ? clone $fieldValue : $fieldValue;
                },
                $model instanceof Resource
                    ? array_keys($model->fieldDefinitions())
                    : $metadata->getFieldNames()
            );
        }

        return $return;
    }

    /**
     * Returns the changes for the given model.
     *
     * @param mixed $model
     *
     * @return array
     */
    public function getChanges($model): array
    {
        $changes = $this->extractChanges(
            $newData = $this->extractData($model),
            $oldData = $this->getOriginalStatus($model)
        );

        $this->logger->debug(
            'Extracted changed data from the object.',
            [
                'changedData' => $changes,
                'class' => get_class($model),
                'id' => $model->getId(),
                'memory' => memory_get_usage(true) / 1024 / 1024,
                'newData' => $newData,
                'objectKey' => $this->objectStates->getHash($model),
                'oldData' => $oldData,
            ]
        );

        return $changes;
    }

    /**
     * Returns the original status for the given model.
     *
     * @param mixed $model
     *
     * @return array
     */
    public function getOriginalStatus($model): array
    {
        return $this->objectStates[$model];
    }

    /**
     * Was the given model changed?
     *
     * This method checks if there is a managed original status and checks its hash against the given model.
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function isChanged($model): bool
    {
        return $this->contains($model)
            ? sha1(json_encode($this->extractData($model))) !== sha1(json_encode($this->getOriginalStatus($model)))
            : false;
    }

    /**
     * Registers the data status of the given model with this manager.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function registerStatus($model)
    {
        if (!$this->contains($model)) {
            $this->objectStates->attach($model, $this->extractData($model));
        }
    }

    /**
     * Checks if the given array is an associative one or has other array childs.
     *
     * @param array $array
     *
     * @return bool
     */
    private function hasNestedArray(array $array): bool
    {
        $isNested = false;

        foreach ($array as $key => $value) {
            if (is_array($value) || !is_numeric($key)) {
                $isNested = true;
                break;
            }
        }

        return $isNested;
    }
}
