<?php

namespace JMCode\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use JMCode\Http\Filter\Request as RequestFilter;

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
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
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
        RequestFilter::method($method);

        if ($this->method === $method) {
            return $this;
        }

        $clone = clone $this;
        $clone->method = $method;

        return $clone;
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
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }
}