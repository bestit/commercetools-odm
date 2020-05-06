<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Core\Client;
use Commercetools\Core\Request\QueryAllRequestInterface;
use Generator;

/**
 * Memory-optimized variant of the commercetools query helper.
 *
 * @package BestIt\CommercetoolsODM\Helper
 */
class GeneratorQueryHelper
{
    /**
     * @var int
     */
    const DEFAULT_PAGE_SIZE = 500;

    /**
     * @param Client $client
     * @param QueryAllRequestInterface $request
     *
     * @return Generator
     */
    public function getAll(Client $client, QueryAllRequestInterface $request): Generator
    {
        $lastId = null;

        $request = $request
            ->sort('id')
            ->limit(static::DEFAULT_PAGE_SIZE)
            ->withTotal(false);

        do {
            if ($lastId !== null) {
                $request = $request->where('id > "' . $lastId . '"');
            }

            $response = $client->execute($request);

            if ($response->isError() || is_null($response->toObject())) {
                break;
            }

            $results = $response->toArray()['results'];

            foreach ($request->map(['results' => $results], $client->getConfig()->getContext()) as $item) {
                yield $item;
            }

            $lastId = end($results)['id'];
        } while (count($results) >= static::DEFAULT_PAGE_SIZE);
    }
}
