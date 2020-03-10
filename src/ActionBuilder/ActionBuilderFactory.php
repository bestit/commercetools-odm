<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use FilesystemIterator;
use Psr\Cache\CacheItemPoolInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;

/**
 * Provides action builders for the source object and its metadata.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder
 * @subpackage ActionBuilder
 * @todo Add caching for field/class; Add Events!
 */
class ActionBuilderFactory implements ActionBuilderFactoryInterface
{
    /**
     * Where are builder classes?
     *
     * @var array
     */
    const CLASS_PATHS = [
        'BestIt\\CommercetoolsODM\\ActionBuilder\\' => __DIR__ . DIRECTORY_SEPARATOR
    ];

    /**
     * The caching pool.
     *
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool = null;

    /**
     * Where are builder classes?
     *
     * @var array
     */
    private $classPaths = [];

    /**
     * ActionBuilderFactory constructor.
     *
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this
            ->setCacheItemPool($cacheItemPool)
            ->setClassPaths(self::CLASS_PATHS);
    }

    /**
     * Gets the action builders for the given object and its field name.
     *
     * @param ClassMetadataInterface $classMetadata
     * @param string $fieldPath The hierarchical path of the fields.
     * @param object $sourceObject
     *
     * @return ActionBuilderInterface[]
     */
    public function getActionBuilders(ClassMetadataInterface $classMetadata, string $fieldPath, $sourceObject): array
    {
        $sourceClass = $classMetadata->getActionsFrom();

        $allBuilders = array_map(function (string $builderClass): ActionBuilderInterface {
            return new $builderClass();
        }, $this->loadActionBuilders());

        $foundBuilders = array_filter($allBuilders, function (ActionBuilderInterface $builder) use (
            $fieldPath,
            $sourceClass
        ) {
            return $builder->supports($fieldPath, $sourceClass);
        });

        $nonStackableBuilders = array_filter($foundBuilders, function (ActionBuilderInterface $builder) {
            return !$builder->isStackable();
        });

        return $nonStackableBuilders ? [reset($nonStackableBuilders)] : $foundBuilders;
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function getCacheItemPool(): CacheItemPoolInterface
    {
        return $this->cacheItemPool;
    }

    /**
     * Where are builder classes?
     *
     * @return array
     */
    public function getClassPaths(): array
    {
        return $this->classPaths;
    }

    /**
     * Loads the action builders from cache or directly out of the file system.
     *
     * @return array
     */
    private function loadActionBuilders():array
    {
        $cachePool = $this->getCacheItemPool();
        $cacheHit = $cachePool->getItem($cacheKey = sha1(__METHOD__));

        if (!$cacheHit->isHit()) {
            $allBuilders = [];
            $classPaths = $this->getClassPaths();

            array_walk(
                $classPaths,
                function (string $path, string $namespace) use (&$allBuilders, $cacheHit) {
                    $path = realpath($path);

                    if ($path) {
                        $path .= DIRECTORY_SEPARATOR;

                        $iterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
                        );

                        foreach ($iterator as $name) {
                            $className = $namespace . str_replace(
                                [$path, DIRECTORY_SEPARATOR, '.php'],
                                ['', '\\', ''],
                                $name
                            );

                            try {
                                $reflection = new ReflectionClass($className);

                                if (!$reflection->isAbstract() && !$reflection->isInterface() &&
                                    $reflection->implementsInterface(ActionBuilderInterface::class)
                                ) {
                                    $allBuilders[] = $className;
                                }
                            } catch (ReflectionException $exc) {
                                // Ignore "broken" classes.
                            }
                        }
                    }
                }
            );

            $cacheHit->set($allBuilders);

            if ($allBuilders) {
                $cachePool->save($cacheHit);
            }
        }

        return $cacheHit->get();
    }


    /**
     * @param CacheItemPoolInterface $cacheItemPool
     *
     * @return ActionBuilderFactory
     */
    private function setCacheItemPool(CacheItemPoolInterface $cacheItemPool): ActionBuilderFactory
    {
        $this->cacheItemPool = $cacheItemPool;

        return $this;
    }

    /**
     * Where are builder classes?
     *
     * @param array $classPaths
     *
     * @return ActionBuilderFactory
     */
    public function setClassPaths(array $classPaths): ActionBuilderFactory
    {
        $this->classPaths = $classPaths;

        return $this;
    }
}
