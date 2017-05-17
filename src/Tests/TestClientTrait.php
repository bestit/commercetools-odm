<?php

namespace BestIt\CommercetoolsODM\Tests;

use ArrayObject;
use Commercetools\Core\Client;
use Commercetools\Core\Client\OAuth\Manager;
use Commercetools\Core\Client\OAuth\Token;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Helps providing a test client for mocking requests and responses.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Tests
 * @version $id$
 */
trait TestClientTrait
{
    /**
     * The cache for the client request history.
     * @var ArrayObject|null
     */
    private $requestCache = null;

    /**
     * Returns a partial mock.
     * @param $originalClassName
     * @param array $methods
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    abstract protected function createPartialMock($originalClassName, array $methods);

    /**
     * Returns a client with mocked responses.
     * @param array ...$responses
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClientWithResponses(...$responses)
    {
        foreach ($responses as &$response) {
            if (is_callable($response)) {
                $response = $response();
            }
        }

        $client = $this->getTestClient($responses);

        return $client;
    }

    /**
     * Returns tTe cache for the client request history.
     * @return ArrayObject
     */
    public function getRequestCache(): ArrayObject
    {
        if (!$this->requestCache) {
            $this->setRequestCache(new ArrayObject());
        }

        return $this->requestCache;
    }

    /**
     * Ceates a test client with the given responses.
     * @param array $responses
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTestClient(array $responses)
    {
        $authMock = $this->createPartialMock(Manager::class, ['getToken']);
        $authMock
            ->method('getToken')
            ->will($this->returnValue(new Token(uniqid())));

        $client = $this->createPartialMock(Client::class, ['getOauthManager']);
        $client
            ->method('getOauthManager')
            ->will($this->returnValue($authMock));

        $mock = new MockHandler($responses);

        $handlerStack = HandlerStack::create($mock);

        $requestCache = $this->getRequestCache();
        $handlerStack->push(Middleware::history($requestCache));

        $client->getHttpClient(['handler' => $handlerStack]);
        $client->getOauthManager()->getHttpClient(['handler' => $handlerStack]);

        return $client;
    }

    /**
     * Sets the cache for the client request history.
     * @param ArrayObject $requestCache
     * @return $this
     */
    public function setRequestCache(ArrayObject $requestCache)
    {
        $this->requestCache = $requestCache;

        return $this;
    }
}
