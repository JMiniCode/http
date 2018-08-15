<?php

namespace JMCode\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 * @package JMCode\Http
 */
class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    protected $requestTarget = '/';

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @return string
     */
    public function getRequestTarget()
    {
        $this->requestTarget;
    }

    /**
     * @param mixed $requestTarget
     * @return RequestInterface
     */
    public function withRequestTarget($requestTarget)
    {

    }

    /**
     * @return string|void
     */
    public function getMethod()
    {
        $this->method;
    }

    /**
     * @param string $method
     * @return RequestInterface
     */
    public function withMethod($method)
    {

    }

    /**
     * @return UriInterface|void
     */
    public function getUri()
    {
        $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {

    }
}